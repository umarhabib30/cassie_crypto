<template>
  <header>
    <div class="fixed">
      <div class="container">
        <div class="header-container">
          <router-link to="/" tag="div" class="logo"></router-link>
          <div class="switcher">
            <!--
            <div class="switch" :class="isCasino ? 'active' : ''" @click="$router.push('/')">
              <web-icon icon="casino"></web-icon>
            </div>
            <template v-if="$isAvailable('phoenixSport')">
              <div class="switch sportSwitch" :class="(!isCasino && sportLink === 'sport' ? 'active' : '')" @click="$router.push('/sport')">
                <web-icon icon="sport"></web-icon>
              </div>
            </template>
            <div class="switch" @click="openSearch">
              <web-icon icon="fal fa-search"></web-icon>
            </div>
            -->
            <div class="c-search">
              <div class="search-input" @click="openSearch">
                <web-icon icon="fal fa-search"></web-icon>
                <input :placeholder="$t('general.search')">
              </div>
            </div>
          </div>

          <content-placeholders v-if="!isGuest && !currencies" class="wallet_loader">
            <content-placeholders-img/>
          </content-placeholders>
          <div class="wallet" v-if="!isGuest && currencies" v-click-outside="() => this.expand = false">
            <div :class="`wallet-switcher ${expand ? 'active' : ''}`">
              <overlay-scrollbars :options="{ scrollbars: { autoHide: 'leave' }, className: 'os-theme-thin-light' }">
                <div v-for="(currency, i) in currencies" v-if="currency.balance" class="option" :key="i" @click="selectCurrency(currency)">
                  <div class="currency">
                    <web-icon :icon="currency.icon" :style="{ color: currency.style }"></web-icon>
                  </div>
                  <div class="wallet-switcher-content">
                    <div>
                      <unit :fiat="fiatView" :to="currency.id" :value="demo ? currency.balance.demo : currency.balance.real"></unit>
                    </div>
                    <span>{{ currency.name }}</span>
                  </div>
                </div>
              </overlay-scrollbars>
              <!--
              <div class="option" @click="$refs.demoSwitch.toggle()">
                <div class="wallet-switcher-content">
                  {{ $t('general.head.wallet_demo') }}
                </div>
                <div @click.stop>
                  <web-switch :value="demo" :onChange="() => $store.commit('setDemo', !demo)" ref="demoSwitch"></web-switch>
                </div>
              </div>-->
              <div class="option" @click="$refs.fiatSwitch.toggle()">
                <div class="wallet-switcher-content">
                  {{ $t('general.head.view_in_fiat') }}
                </div>
                <div @click.stop>
                  <web-switch :value="fiatView" :onChange="() => $store.commit('setFiatView', !fiatView)" ref="fiatSwitch"></web-switch>
                </div>
              </div>
              <!--
              <div class="option select-option">
                <div class="wallet-switcher-content" style="padding-right: 15px; width: 100%">
                  {{ $t('general.unit') }}:
                  <select @change="$store.commit('setUnit', selectedUnit)" v-model="selectedUnit">
                    <option value="btc">BTC</option>
                    <option value="mbtc">milliBTC</option>
                    <option value="bit">microBTC</option>
                    <option value="satoshi">Satoshi</option>
                  </select>
                </div>
              </div>
              -->
            </div>
            <div @click="expand = !expand">
              <div class="btn btn-secondary icon">
                <web-icon :icon="currencies[currency].icon" v-if="currencies[currency]" :style="{ color: currencies[currency].style }"></web-icon>
                <i class="fal fa-angle-down"></i>
              </div>
              <div class="balance">
                <unit :fiat="fiatView" :to="currency" :value="currencies[currency].balance[demo ? 'demo' : 'real']" :class="{ 'blur': isCasinoGameURL }"></unit>
                <!--
                <transition-group mode="out-in" name="balance-a" :style="{ position: 'absolute' }">
                  <span :key="`key-${i}`" v-for="(animate, i) in animated" :class="`animated text-${animate.diff.action === 'subtract' ? 'danger' : 'success'}`">
                      <unit :fiat="fiatView" :to="currency" :value="animate.diff.value"></unit>
                  </span>
                </transition-group>
                -->
              </div>
            </div>
            <div class="btn btn-green wallet-btn">
              <div class="balance" @click="demo ? openDemoBalanceModal() : openWalletModal()">
                {{$t('general.deposit_btn')}}              
            </div>
            </div>
          </div>
          
          <div class="right">
            <div v-if="isGuest">
              <button class="btn btn-login" @click="openAuthModal('auth')">{{ $t('general.auth.login') }}</button>
                <button class="btn btn-primary" @click="openAuthModal('register')">
                  <span>
                    {{$t('general.auth.register')}}
                  </span>
                </button>
            </div>
            <div v-else>
              <router-link tag="img" :to="`/profile/${user.user._id}`" :src="user.user.avatar"></router-link>
            </div>
          </div>
        
        </div>
      </div>
    </div>
  </header>
</template>

<script>
  import Bus from '../../bus';
  import {mapGetters} from 'vuex';
  import AuthModal from "../modals/AuthModal.vue";
  import DemoBalanceModal from "../modals/DemoBalanceModal.vue";
  import TermsModal from "../modals/TermsModal.vue";
  import WalletModal from "../modals/WalletModal.vue";

  export default {
    computed: {
      ...mapGetters(['user', 'isGuest', 'demo', 'unit', 'currency', 'currencies', 'sidebar', 'fiatView', 'sidebar']),
    },
    data() {
      return {
        expand: false,
        selectedUnit: 'btc',
        animated: [],

        nonLocalFound: false
      }
    },
    mounted() {
      this.selectedUnit = this.unit;

      Bus.$on('event:balanceModification', (e) => {
        setTimeout(() => {
          const currencies = this.currencies;
          currencies[e.currency].balance = {
            real: e.balance,
            //demo: e.demo_balance
          };
          this.$store.dispatch('setCurrencies', currencies);

          this.animated.push(e);
          setTimeout(() => this.animated = this.animated.filter((a) => a !== e), 1000);
        }, e.delay);
      });
    },
    beforeRouteEnter(to, from, next) {
      next(vm => {
        vm.updateCasinoGameStatus(to);
      });
    },

    beforeRouteUpdate(to, from, next) {
      this.updateCasinoGameStatus(to);
      next();
    },
    methods: {
      updateCasinoGameStatus(to) {
        this.isCasinoGame = to.path.includes('/casino/game');
      },
      openWalletModal() {
        WalletModal.methods.open();
      },
      openSearch() {
        Bus.$emit('search:toggle');
      },
      openAuthModal(type) {
        AuthModal.methods.open(type);
      },
      openDemoBalanceModal() {
        DemoBalanceModal.methods.open();
      },
      openFaucetModal() {
        if (this.isGuest) return this.openAuthModal('auth');
        this.$router.push('/bonus');
      },
      openTerms(type) {
        TermsModal.methods.open(type);
      },
      selectCurrency(currency) {
        this.$store.commit('setCurrency', currency.id);
        this.setCookie('currency', currency.id, 365);
        this.expand = false;
      },
    }
  }
</script>

<style lang="scss">
  @import "resources/sass/variables";

  header {
    height: $header-height;
    display: initial !important;
    flex-shrink: 0;
    z-index: 38001;

    button {
      font-size: 0.9em !important;
      padding: 10px 20px !important;
    }

    .fixed {
      height: $header-height;
      position: sticky;
      left: 0;
      top: 0;
      width: 100%;
      z-index: 10001;
      //background: #111111;
      background: rgb(28 25 23 / .75);
      backdrop-filter: blur(10px);
      border-bottom: 1px solid rgba(41, 37, 36);

      @media(max-width: 770px) {
        background: rgb(28, 25, 23);
        backdrop-filter: none;
      }

      @include themed() {
        box-shadow: t('shadow');
      }

      .sidebar-switch {
        opacity: .4;
        cursor: pointer;
        margin-left: 15px;
      }

      .header-container {
        display: flex;
        align-items: center;
        height: $header-height;

        .header_search_bar {
          position: relative;
          cursor: pointer;
          margin-left: auto;

          @media(max-width: 1500px) {
            display: none;
          }

          input {
            cursor: pointer !important;
            padding-left: 55px !important;
            @include themed() {
              background: t('input') !important;
              border: 1px solid t('border') !important;
            }
            pointer-events: none !important;
            border-radius: 50px;
          }

          i, svg {
            position: absolute;
            top: 15px;
            left: 25px;
          }
        }

        .switcher {
          display: flex;

          @media(max-width: 991px) {
            display: none;
          }

          .switch {
            @include themed() {
              transition: background .3s ease, opacity .3s ease;
              opacity: .8;
              display: flex;
              align-items: center;
              justify-content: center;
              margin-right: 15px;
              cursor: pointer;
              padding: 15px;
              border-radius: 50%;
              background: t('wallet');

              &:last-child {
                margin-right: 0;
              }

              svg, i {
                transition: color .3s ease;
                color: inherit;
                display: flex;
                align-items: center;
                justify-content: center;
              }

              &.active {
                background: t('header-block');

                .name {
                  font-weight: 600;
                }

                svg, i {
                  color: t('secondary');
                }
              }
            }
          }

          .c-search {
            @include themed() {
              display: flex;
              align-items: center;
            }

            .search-input {
              position: relative;
              cursor: pointer;
              width: 100%;

              input {
                pointer-events: none;
                padding: 5px 5px 5px 45px;
                padding-left: 50px;
                background: rgb(28,25,23);
                border-radius: .75rem;
                color: rgb(120 113 108);
                box-shadow: inset 0 0 0 0px #fff, inset 0 0 0 calc(1px + 0px) rgb(68 64 60 / 1), 0 1px 2px 0 #0000000d;

                &:hover, &:active, &:focus {
                  background-color: rgb(41, 37, 36);
                }

                @include themed() {
                  &::placeholder {
                    color: t('link');
                  }
                }
              }

              &:hover {
                input {
                  background-color: rgb(41, 37, 36) !important;
                }
              }

              svg, i {
                position: absolute;
                top: 50%;
                transform: translateY(-50%);
                left: 20px;
              }
            }
          }
        }

        @media(max-width: 1000px) {
          padding: 0 0 0 0;
        }

        .menuSwitcher {
          display: none;
          margin-left: 15px;
          opacity: .5;
          transition: opacity .3s ease;
          cursor: pointer;

          &:hover {
            opacity: 1;
          }
        }
      }

      @include themed() {
        //background: t('header');

        .logo {
          height: 40px;
          width: 100px;
          display: flex;
          cursor: pointer;
          background: url('/img/misc/logo.png') no-repeat center;
          background-size: contain;
          margin-left: unset;

          @media(min-width: 991px) {
            display: none;
          }
        }
      }

      .right {
        display: flex;
        align-items: center;
        margin-left: auto;

        &.ml-auto {
          margin-left: auto;
        }

        img {
          width: 45px;
          height: 45px;
          border-radius: 50%;
          cursor: pointer;
          margin-left: 10px;
        }

        @media(max-width: 991px) {
          [data-notification-view] {
            display: none !important;
          }
        }

        .action {
          display: flex;
          align-content: center;
          position: relative;
          background: rgb(25, 32, 46);
          border: 1px solid #112D35;
          border-radius: 4px;

          .notification {
            position: absolute !important;
            top: 7px !important;
            left: 19px !important;
            width: 8px !important;
            height: 8px !important;
          }

          i {
            font-size: 1.15em;
            margin: 10px;
            opacity: 0.35;
            transition: opacity 0.3s ease;

            &:hover {
              opacity: 1;
              cursor: pointer;
            }
          }
        }

        .btn {
          padding: 10px 30px !important;
          border-radius: 200px !important;
          font-weight: bold;
        }

        .btn-login {
          background-color: rgb(28, 25, 23) !important;
          margin-right: 10px;
          box-shadow: inset 0 0 #fff, inset 0 0 0 1px #44403c, 0 1px 2px #0000000d;
          &:hover {
            background-color: rgb(41, 37, 36) !important;
          }
        }

        .btn-primary {
          --offset: 1px;
          position: relative;
          overflow: hidden;
          background-color: rgb(28, 25, 23);
          box-shadow: inset 0 0 #fff, inset 0 0 0 1px #44403c, 0 1px 2px #0000000d;
        }

        .btn-primary::before { 
            content: '';
            background: conic-gradient(transparent 270deg, rgb(0 220 130), transparent);
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            aspect-ratio: 1;
            width: 100%;
            animation: rotate 2s linear infinite;
        }

        .btn-primary::after {
            content: '';
            background: inherit;
            border-radius: inherit;
            position: absolute;
            inset: var(--offset);
            height: calc(100% - 2 * var(--offset));
            width: calc(100% - 2 * var(--offset));
        }

        .btn-primary span {
          position: relative;
          z-index: 1;
          color: #fff;
        }

        @keyframes rotate {
            from {
                transform: translate(-50%, -50%) scale(1.4) rotate(0turn);
            }

            to {
                transform: translate(-50%, -50%) scale(1.4) rotate(1turn);
            }
        }

      }
    }
  }

  @include only_safari('header', (
    display: contents !important
  ));

  .balance .blur {
    -webkit-filter: blur(5px);
    filter: blur(5px);
  }	

  .balance-a-enter-active, .balance-a-leave-active {
    transition: all 1s ease;
  }

  .balance-a-enter {
    margin-top: 25px;
    opacity: 1 !important;
  }

  .balance-a-enter-to {
    margin-top: 0;
    opacity: 0 !important;
  }

  .balance-a-leave, .balance-a-leave-to {
    opacity: 0 !important;
  }
</style>
