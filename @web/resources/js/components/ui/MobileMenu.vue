<template>
  <div>
    <div class="sportWidgetMobile" v-if="$route.path.startsWith(`/${sportLink ?? 'sport'}/game`)">
      <div class="header" @click="activeLiveStream = !activeLiveStream">
        <i :class="`fal fa-chevron-${activeLiveStream ? 'down' : 'up'}`"></i>
        {{ $t('sport.liveStream') }}
        <div class="pulsating-circle"></div>
      </div>
      <div class="content" :class="activeLiveStream ? 'active' : ''">
        <div id="mobileWidget"></div>
      </div>
    </div>
    <div class="mobile-menu">
      <div class="control" @click="$store.dispatch('toggleChat', false); toggleSidebarMobile()">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#fff"><path fill="#fff" d="M13 17H5c-.55 0-1 .45-1 1s.45 1 1 1h8c.55 0 1-.45 1-1s-.45-1-1-1m6-8H5c-.55 0-1 .45-1 1s.45 1 1 1h14c.55 0 1-.45 1-1s-.45-1-1-1M5 15h14c.55 0 1-.45 1-1s-.45-1-1-1H5c-.55 0-1 .45-1 1s.45 1 1 1M4 6c0 .55.45 1 1 1h14c.55 0 1-.45 1-1s-.45-1-1-1H5c-.55 0-1 .45-1 1"></path><path fill="#fff" fill-opacity="0.9" d="M13 17H5c-.55 0-1 .45-1 1s.45 1 1 1h8c.55 0 1-.45 1-1s-.45-1-1-1m6-8H5c-.55 0-1 .45-1 1s.45 1 1 1h14c.55 0 1-.45 1-1s-.45-1-1-1M5 15h14c.55 0 1-.45 1-1s-.45-1-1-1H5c-.55 0-1 .45-1 1s.45 1 1 1M4 6c0 .55.45 1 1 1h14c.55 0 1-.45 1-1s-.45-1-1-1H5c-.55 0-1 .45-1 1"></path></svg>
        <div>Menu</div>
      </div>
      <div class="control">
        <div class="mobile-menu-center" @click="openWalletModal">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#fff"><path fill="#fff" fill-rule="evenodd" d="M13.5 10h-8a3.5 3.5 0 1 1 0-7H20a1 1 0 1 1 0 2H5.5a1.5 1.5 0 1 0 0 3h8z" clip-rule="evenodd"></path><path fill="#fff" fill-rule="evenodd" d="M4 6.5V12H2V6.5z" clip-rule="evenodd"></path><path fill="#fff" d="M19 21H5c-.943 0-1.414 0-1.707-.293S3 19.943 3 19V9h16c.943 0 1.414 0 1.707.293S21 10.057 21 11v1h-3a3 3 0 1 0 0 6h3v1c0 .943 0 1.414-.293 1.707S19.943 21 19 21"></path><path fill="#fff" fill-rule="evenodd" d="M22 17v2.054c0 .425 0 .837-.046 1.177-.051.383-.177.82-.54 1.183s-.8.489-1.184.54c-.34.046-.751.046-1.176.046H4.946c-.424 0-.837 0-1.177-.046-.383-.051-.82-.177-1.183-.54s-.489-.8-.54-1.184C2 19.89 2 19.48 2 19.055V8h17.054c.425 0 .837 0 1.177.046.383.051.82.177 1.183.54s.489.8.54 1.183c.046.34.046.752.046 1.177V13h-4a2 2 0 1 0 0 4zM3 19c0 .943 0 1.414.293 1.707S4.057 21 5 21h14c.943 0 1.414 0 1.707-.293S21 19.943 21 19v-1h-3a3 3 0 0 1 0-6h3v-1c0-.943 0-1.414-.293-1.707S19.943 9 19 9H3z" clip-rule="evenodd"></path></svg>
        </div>
        <div>{{$t('general.deposit_btn')}}</div>
      </div>
      <div class="control" @click="openSearch">
        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" fill="#fff" stroke="#fff"><path fill="#fff" fill-rule="evenodd" d="M11.5 18a7 7 0 1 0 0-14 7 7 0 0 0 0 14m0-12a5 5 0 0 0-5 5 1 1 0 1 0 2 0 3 3 0 0 1 3-3 1 1 0 1 0 0-2" clip-rule="evenodd"></path><path stroke="#fff" stroke-linecap="round" stroke-opacity="0.75" stroke-width="2" d="m20.5 20-2-2"></path></svg>
        <div>{{$t('general.search')}}</div>
      </div>
    </div>
  </div>
</template>

<script>
  import {mapGetters} from 'vuex';
  import Bus from "../../bus.js";
  import WalletModal from "../modals/WalletModal.vue";
  import AuthModal from "../modals/AuthModal.vue";

  export default {
    data() {
      return {
        betSlipSize: 0,
        activeLiveStream: false
      }
    },
    watch: {
      activeLiveStream() {
        if(this.activeLiveStream) Bus.$emit('sport:initializeMobileWidget');
      },
      $route() {
        this.activeLiveStream = false;
      }
    },
    created() {
      // setInterval(() => {
      //   this.betSlipSize = Bus.$retrieve('betSlip:size');
      // }, 100);
    },
    methods: {
      toggleSidebarMobile() {
        Bus.$emit('sidebar:toggleMobile');
      },
      openAuthModal(type) {
        AuthModal.methods.open(type);
      },
      openWalletModal() {
        if (this.isGuest && !this.isHovered) {
          this.openAuthModal('auth');
        } else {
          WalletModal.methods.open();
        }
      },
      openSearch() {
        Bus.$emit('search:toggle');
      }
    },
    computed: {
      ...mapGetters(['theme', 'games', 'isGuest', 'user'])
    }
  }
</script>

<style lang="scss">
  @import "resources/sass/themes";

  #mobileWidget {
    max-height: 320px;
    max-width: 300px;
    margin: auto;

    .notStarted {
      text-align: center;
      margin-bottom: 10px;
      opacity: .7;
    }

    .sr-lmt-plus__footer-wrapper {
      display: none;
    }
  }

  .sportWidgetMobile {
    position: fixed;
    bottom: 75px;
    left: 0;
    width: 100%;
    z-index: 5;
    display: flex;
    flex-direction: column;

    @include themed() {
      border-top: 1px solid t('border');
      background: t('body');
    }

    @media(min-width: 991px) {
      display: none;
    }

    .header {
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      padding: 10px;
      cursor: pointer;
      text-transform: uppercase;

      i {
        margin-right: 10px;
      }

      .pulsating-circle {
        margin-left: 10px;
      }
    }

    .content {
      display: none;
      @include themed() {
        background: t('sidebar');
      }

      &.active {
        display: block;
      }
    }
  }
  .mobile-menu-center {
    background-color: #00dc82;
    /* margin-top: -34px; */
    /* margin-bottom: 20px; */
    /* padding: 10px; */
    top: -32px;
    border-radius: 100%;
    position: absolute;
    /* margin-bottom: auto; */
    width: 48px;
    height: 48px;
    border: 7px solid #39453f;
    display: flex;
    align-items: center;
    justify-content: center;
  }
</style>
