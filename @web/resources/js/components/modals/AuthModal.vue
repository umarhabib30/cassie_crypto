<script>
  import Bus from '../../bus.js';
  //import VueRecaptcha from 'vue-recaptcha';

  import ForgotPasswordModal from "./ForgotPasswordModal.vue";

  export default {
    methods: {
      open(type) {
        Bus.$emit('modal:new', {
          name: 'register',
          component: {
            //components: {VueRecaptcha},
            data() {
              return {
                type: type,
                email: '',
                login: '',
                password: '',
                //recaptchaKey: import.meta.env.VITE_RECAPTCHA_KEY,
               // captchaPayload: null
              }
            },
            created() {
              if (typeof URLSearchParams === 'function') {
                const params = new URLSearchParams(window.location.search);
                if (params.has('c')) this.inviteCode = params.get('c');
              }

              Bus.$on('login:fail', () => this.$toast.error(this.$i18n.t('general.auth.wrong_credentials')), true);
              Bus.$on('login:success', () => Bus.$emit('modal:close'), true);
              Bus.$on('register:fail', (e) => {
                if (e.response.data.code === 1) {
                  this.inviteCode = '';
                  return this.$toast.error('Ongeldige uitnodigingscode.');
                }

                const errors = e.response.data.errors;
                Object.keys(errors).forEach(key => {
                  const values = errors[key];
                  switch (key) {
                    case 'email': {
                      values.forEach(value => {
                        if (value === 'validation.email')
                          this.$toast.error('Ongeldig e-mailadres');
                        else if (value === 'validation.unique')
                          this.$toast.error('Dit e-mailadres is al geregistreerd');
                      });
                      break;
                    }
                    case 'name': {
                      values.forEach(value => {
                        if (value === 'validation.regex')
                          this.$toast.error('Uw login heeft minder dan 4 tekens of bevat ongeldige symbolen');
                        else if (value === 'validation.unique')
                          this.$toast.error('Deze login is al geregistreerd, kies iets anders');
                      });
                      break;
                    }
                    case 'password': {
                      values.forEach(value => {
                        if (value === 'validation.min.string')
                          this.$toast.error('Wachtwoord moet minimaal 5 tekens bevatten');
                      });
                      break;
                    }
                  }
                });
              });
            },
            methods: {
              openForgotPasswordModal() {
                Bus.$emit('modal:close');
                ForgotPasswordModal.methods.open();
              },
              /*onVerify(response) {
                this.captchaPayload = response;
              },
              onExpired() {
                this.captchaPayload = null;
              },
              */
              done() {
                const login = () => {
                  this.$store.dispatch('login', {
                    login: this.login,
                    password: this.password,
                    //captcha: this.captchaPayload
                  }).catch(() => this.$toast.error(this.$i18n.t('general.auth.wrong_credentials')));
                };

                if (this.type === 'register') {
                  window.axios.post('/auth/register', {
                    email: this.email,
                    name: this.login,
                    password: this.password,
                    //captcha: this.captchaPayload
                  }, {
                    withCredentials: true
                  }).then(({data}) => {
                    this.$store.dispatch('setUserData', data);
                    this.$store.dispatch('updateData');
                    Bus.$emit('login:success');
                    this.$toast.success('Succesvol geregistreerd');
                  }).catch((e) => Bus.$emit('register:fail', e));
                } else login();
              }
            },
            template: `
              <div>
	            <div class="auth-promo">
              <img class="auth-promo-logo" src="/img/misc/logo.png" alt="">
              <p class="auth-promo-text">{{ $t('general.auth.auth_promo_text') }}</p>
              <img class="auth-promo-image" src="/img/misc/hero-slider-1.png" alt="">
              </div>
              <div class="auth-block-1">
                <div class="auth-tabs">
                  <div class="auth-tab" @click="type = 'auth'" :class="type === 'auth' ? 'active' : ''">
                    {{ $t('general.auth.login') }}
                  </div>
                  <div class="auth-tab" @click="type = 'register'" :class="type === 'register' ? 'active' : ''">
                    {{ $t('general.auth.register') }}
                  </div>
                </div>

                <div class="mt-2 mb-2" v-if="type === 'register'">
                  <input
                    v-model="email" type="email" :placeholder="$t('general.auth.credentials.email')">
                </div>
                <div class="mt-2 mb-2">
                  <input v-model="login" type="text" :placeholder="$t('general.auth.credentials.login')">
                </div>
                <div class="mt-2 mb-2">
                  <input v-model="password" type="password" :placeholder="$t('general.auth.credentials.password')">
                </div>

                
                <span v-if="type === 'auth'" class="forgotPassword" @click="openForgotPasswordModal">{{ $t('general.auth.forgot_password') }}</span>

                <!-- theme="dark" 
                 <vue-recaptcha theme="dark" class="recaptcha" :sitekey="recaptchaKey" :loadRecaptchaScript="true"
                                @verify="onVerify" @expired="onExpired"></vue-recaptcha>
                -->

                <button class="btn btn-primary btn-block p-3"  @click="done">
                  {{ $t('general.auth.' + (type === 'auth' ? 'login' : 'register')) }}
                </button>

                <div class="divide-auth"></div>
                <div class="notice">{{ $t('general.auth.notice') }}</div>
              </div>
              </div>`
          }
        });
      }
    }
  }
</script>

<style lang="scss">
  @import "resources/sass/variables";

  .xmodal.register {
    width: 700px !important;

    .divide-auth {
      height: 1px;
      margin-top: 20px;
      margin-bottom: 20px;
      background: white;
      opacity: 0.2;
    }

    .forgotPassword {
      font-size: 13px;
      margin-bottom: 10px;
      margin-left: auto;
      cursor: pointer;
    }

    .notice {
      font-weight: 500;
      font-size: 12px;
      line-height: 15px;
    }

    .auth-tabs {
      display: flex;
      align-items: center;
      justify-content: center;

      .auth-tab {
        cursor: pointer;
        background: transparent;
        transition: background .3s ease, color .3s ease;
        border-radius: 25px;
        padding: 10px 25px;
        margin-bottom: 15px;
        @include themed() {
          color: t('text');
        }
        margin-right: 5px;

        &:last-child {
          margin-right: 0;
        }

        &.active {
          color: white;

          @include themed() {
            border: 1px solid t('secondary');
          }
        }
      }
    }

    .heading {
      padding: unset !important;
      margin-bottom: 15px;
    }

    .auth-promo {
      padding: 20px 0px 0px 0px;
      width: 350px;
      min-height: 465px;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;

      img {
        width: 100%;
        //border-radius: 20px;
        //border: 1px solid rgb(0, 220, 130);
      }

      .auth-promo-logo {
        width: 200px;
        margin-bottom: 10px;
      }

      .auth-promo-text {
        margin-bottom: 30px;
      }
    }

    .auth-block-1 {
      padding: 20px;
      width: 350px;
      display: flex;
      flex-direction: column;
    }

    .modal_content {
      padding: unset !important;
      flex-direction: unset !important;
    }

    .recaptcha {
      margin-top: 15px;
      margin-bottom: 15px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .auth-button-group {
      display: flex;

      button {
        position: relative;
        display: inline-flex;
        width: 33.333%;
        margin: 0 5px;
        padding: 15px;
        align-items: center;
        justify-content: center;

        i {
          position: absolute;
        }
      }
    }

    .auth-footer {
      text-align: center;
      margin-top: 15px;

      div:first-child {
        font-size: 11px;
        margin-bottom: 10px;
      }

      span:first-of-type, span:last-of-type {
        @include themed() {
          color: t('link');
          transition: color 0.3s ease;
          cursor: pointer;
          &:hover {
            color: t('link-hover');
          }
        }
      }

      .or {
        display: inline-flex;
        margin: 7px 5px 0;
        cursor: default;
        user-select: none;
        @include themed() {
          background: t('link');
        }
        width: 1px;
        height: 9px;
      }
    }
  }

  @media(max-width: 774px) {
    .xmodal.register {
      width: 344px !important;

      .auth-promo {
        display: none;
      }
    }
  }
</style>
