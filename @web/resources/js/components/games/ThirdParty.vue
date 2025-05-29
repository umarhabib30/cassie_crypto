<template>
  <div>
    <div class="thirdPartyContainer">
      <transition name="fade" mode="out-in">
        <div :key="1" v-if="loading" class="loading">
          <loader></loader>
        </div>
      </transition>

      <sidebar-play ref="tpPlay" class="d-none"></sidebar-play>

      <iframe id="frame" :src="url" v-if="url" frameborder="0"
          allow="accelerometer; autoplay; encrypted-media; gyroscope" allowfullscreen></iframe>


      <div class="thirdPartySidebar" v-if="currencies[currency].price < 0">
        <div class="conversion">{{ $t('third-party.unavailable_currency') }}</div>
      </div>
    </div>
    <div class="thirdPartyContainerFooter" v-if="url && !isMobile()">
      <button @click="toggleFullscreen" class="btn"><svg xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 16 16" width="30px" class="h-4 w-5 fill-current text-text-default"><path fill-opacity="0.87" d="M4 9.333c-.367 0-.667.3-.667.667v2c0 .367.3.667.667.667h2c.366 0 .666-.3.666-.667s-.3-.667-.666-.667H4.666V10c0-.367-.3-.667-.666-.667m0-2.666c.366 0 .666-.3.666-.667V4.667H6c.366 0 .666-.3.666-.667s-.3-.667-.666-.667H4c-.367 0-.667.3-.667.667v2c0 .367.3.667.667.667m7.333 4.666H10c-.367 0-.667.3-.667.667s.3.667.667.667h2c.366 0 .666-.3.666-.667v-2c0-.367-.3-.667-.666-.667s-.667.3-.667.667zM9.333 4c0 .367.3.667.667.667h1.333V6c0 .367.3.667.667.667.366 0 .666-.3.666-.667V4c0-.367-.3-.667-.666-.667h-2c-.367 0-.667.3-.667.667"></path></svg></button>
    </div>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex';
  import AuthModal from "../modals/AuthModal.vue";

  export default {
    data() {
      return {
        loading: false,
        url: null,
        expand: false
      };
    },
    mounted() {
      if (this.currencies[this.currency].price > 0) {
        setTimeout(() => this.$refs.tpPlay.onClick(), 100);
        this.loading = true;
      }
    },
    watch: {
      currency() {
        axios.post('/api/update/currency', {currency: this.currency}).then(({data}) => {
          // let tmpUrl = this.url;
          // this.url = null;
          // this.url = tmpUrl;
          //this.$router.push('/casino/game/' + this.gameInstance.game.id)
          this.$router.go();
        });
      }
    },
    computed: {
      ...mapGetters(['gameInstance', 'games', 'currencies', 'currency', 'demo', 'isGuest'])
    },
    methods: {
      openAuthModal() {
        AuthModal.methods.open('auth');
      },
      callback(response) {
        axios.post('/api/update/currency', {currency: this.currency}).then(({data}) => {
          if (this.isMobile()) {
            window.location.replace(response.link);
          } else {
            this.url = response.link;
          }
          this.loading = false;
        });
      },
      getClientData() {
        return {};
      },
      getSidebarComponents() {
        return [];
      },
      isMobile() {
        return /Mobi|Android/i.test(navigator.userAgent);
      },
      toggleFullscreen() {
        const iframe = document.getElementById('frame');
        if (iframe.requestFullscreen) {
          iframe.requestFullscreen();
        } else if (iframe.mozRequestFullScreen) { // Firefox
          iframe.mozRequestFullScreen();
        } else if (iframe.webkitRequestFullscreen) { // Chrome, Safari and Opera
          iframe.webkitRequestFullscreen();
        } else if (iframe.msRequestFullscreen) { // IE/Edge
          iframe.msRequestFullscreen();
        }
      }
    }
  }
</script>


<style lang="scss" scoped>
  @import "../../../sass/variables";

  .thirdPartyContainer {
    width: 100%;
    margin: auto;
    height: calc(100vh - #{$header-height} - 70px) !important;
    overflow: hidden;
    position: relative;

    .loading {
      @include themed() {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        position: absolute;
        height: 100%;
        width: 100%;
        top: 0;
        left: 0;
        z-index: 100;

        .warning {
          font-weight: 600;
        }

        .btn {
          margin-top: 15px;
          width: 130px;
        }
      }
    }
    

    iframe {
      border: none;
      width: 100%;
      min-height: 100%;
      position: absolute;
      z-index: 200;
      top: 0;
      left: 0;
    }
  }

  .thirdPartyContainerFooter {
      padding: 20px;
    }
</style>
