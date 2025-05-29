<template>
  <div class="home-head">
    <div class="slider">
      <div class="glide" id="slider">
        <div class="glide__track" data-glide-el="track">
          <ul class="glide__slides">
            <li class="glide__slide">
              <div class="welcome_bonus">
                <div class="welcome_left">
                  <h1>{{$t('general.slider.slider_1_title')}}</h1>
                  <p>{{$t('general.slider.slider_1_desc')}}</p>
                  <router-link to="/WelcomeBonus">{{$t('general.slider.slider_terms')}}</router-link>
                  <div class="moving-line-btn">
                    <button class="btn btn-green" @click="handleButtonClick">{{ buttonText }}</button>
                  </div>
                </div>
                <div class="welcome_right">
                  <img src="/img/misc/hero-slider-1.png" alt="img">
                </div>
              </div>
            </li>
            <li class="glide__slide">
              <div class="welcome_bonus prizes_bg">
                <img src="/img/misc/hero-slider-3.png" class="desktop-image" alt="img">
                <img src="/img/misc/hero-slider-3-mobile.png" class="mobile-image" alt="img">
                <div class="welcome_text">
                  <h1>{{$t('general.slider.slider_2_title')}}</h1>
                  <p>{{$t('general.slider.slider_2_desc')}}</p>
                  <div class="moving-line-btn">
                    <button class="btn btn-green" @click="handleButtonClick">{{ buttonText }}</button>
                  </div>
                </div>
              </div>
            </li>
            <li class="glide__slide">
              <div class="welcome_bonus welcome_video">
                <div class="welcome_left">
                  <video ref="promoVideo" autoplay loop muted playsinline preload="auto" poster="/img/misc/gates-of-olympus2024-poster.png">
                    <source src="/img/misc/gates-of-olympus2024.mp4" type="video/mp4">
                  </video>
                </div>
                <div class="welcome_right">
                  <div class="welcome_text">
                    <h1>{{$t('general.slider.slider_3_title')}}</h1>
                    <p>{{$t('general.slider.slider_3_desc')}}</p>
                    <div class="moving-line-btn">
                      <button class="btn btn-green" @click="handleButtonClick">{{ buttonText }}</button>
                    </div>
                  </div>
                </div>
              </div>
            </li>
            <li class="glide__slide">
              <div class="welcome_bonus welcome_bg">
                <img src="/img/misc/hero-slider-5.png" alt="img">
                <div class="welcome_text">
                  <h1>{{$t('general.slider.slider_4_title')}}</h1>
                  <p>{{$t('general.slider.slider_4_desc')}}</p>
                  <div class="moving-line-btn">
                    <button class="btn btn-green" @click="handleButtonClick">{{ buttonText }}</button>
                  </div>
                </div>
              </div>
            </li>
          </ul>
        </div>
        <!--
        <div class="glide__arrows" data-glide-el="controls">
          <button class="glide__arrow glide__arrow--left" data-glide-dir="<"></button>
          <button class="glide__arrow glide__arrow--right" data-glide-dir=">"></button>
        </div>
        -->
        <div class="glide__bullets" data-glide-el="controls[nav]">
          <button class="glide__bullet" data-glide-dir="=0"></button>
          <button class="glide__bullet" data-glide-dir="=1"></button>
          <button class="glide__bullet" data-glide-dir="=2"></button>
          <button class="glide__bullet" data-glide-dir="=3"></button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Glide from '@glidejs/glide';
import { mapGetters } from 'vuex';
import AuthModal from "../modals/AuthModal.vue";
import WalletModal from "../modals/WalletModal.vue";

export default {
  data() {
    return {
      showPlayButton: false,
      isMobile: false
    };
  },
  computed: {
    ...mapGetters(['user', 'isGuest']),
    buttonText() {
      return this.isGuest ? this.$t('general.slider.slider_guest_btn') : this.$t('general.slider.slider_user_btn');
    }
  },
  mounted() {
    this.initSlider();
    this.setupVideo();
    this.checkIfMobile();
    window.addEventListener('resize', this.checkIfMobile);
  },
  beforeDestroy() {
    window.removeEventListener('resize', this.checkIfMobile);
  },
  methods: {
    initSlider() {
      this.$nextTick(() => {
        new Glide('#slider', {
          type: 'carousel',
          perView: 1,
          autoplay: 4500,
          //autoplay: false, // Disable autoplay
          hoverpause: true,
        }).mount();
      });
    },
    setupVideo() {
      this.$nextTick(() => {
        const video = this.$refs.promoVideo;
        if (video) {
          video.addEventListener('loadeddata', () => {
            this.showPlayButton = this.isMobile; // Show play button on load if mobile
            if (!this.isMobile) {
              video.play().catch(error => {
                console.error("Error attempting to play the video:", error);
              });
            }
          });
        }
      });
    },
    playVideo() {
      const video = this.$refs.promoVideo;
      if (video) {
        video.play().then(() => {
          this.showPlayButton = false; // Hide play button after play starts
        }).catch(error => {
          console.error("Error attempting to play the video:", error);
        });
      }
    },
    handleButtonClick() {
      if (this.isGuest) {
        this.openAuthModal('register');
      } else {
        this.openWalletModal();
      }
    },
    openWalletModal() {
      WalletModal.methods.open();
    },
    openAuthModal(type) {
      AuthModal.methods.open(type);
    },
    checkIfMobile() {
      this.isMobile = window.innerWidth <= 768;
    }
  }
}
</script>


<style scoped lang="scss">
  .welcome_bonus {
    display:flex;
    align-items: center;
    height: 100%;
    background-color: rgb(28,25,23);
    //background-image: linear-gradient(150deg, #292524, #524e4d);
    box-shadow: inset 0 0 #fff, inset 0 0 0 1px #44403c, 0 1px 2px #0000000d;
    border-radius: .75rem;
    padding: 48px;
    background-image: url('/img/misc/border.png');
    background-size: 26px;
    @media(max-width: 500px) {
      flex-direction: column-reverse;
      padding: 20px;
      gap: 0px;
      margin-top: -10vh;
    }
    .welcome_left,
    .welcome_right {
      width: 50%;
      @media(max-width: 500px) {
        width: 100%;
      }
    }
    img {
      max-width: 100%;
    }
    a {
      margin-bottom: 18px;
      display: block;
    }
    .moving-line-btn  {
      position: relative;
      overflow: hidden;
      border-radius: .375rem;
      width: fit-content;
    }
    .moving-line-btn:before {
        content: "";
        position: absolute;
        height: 150%;
        width: 2px;
        background-color: #fff;
        left: -40px;
        top: -10px;
        transform: rotate(45deg);
        box-shadow: 0 0 6px 6px #fff, 0 0 8px 8px hsla(0,0%,100%,.6), 0 0 12px 9px hsla(0,0%,100%,.4);
        z-index: 1;
        animation: movingBlink 2s cubic-bezier(.35,.05,.36,1) infinite;
    }
  }

  .prizes_bg {
    padding: 0px;
    overflow: hidden;

    @media(max-width: 500px) {
      flex-direction: column;
      padding: 1px 20px;
      background: #111111;
    }

    .desktop-image {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .mobile-image {
      display: none;
      width: 240px;
    }

    .welcome_text {
      position: absolute;
      width: 48%;
      left: 48px;
    }


    @media(max-width: 500px) {
      .desktop-image {
        display: none;
      }
      
      .mobile-image {
        display: block;
      }

      .welcome_text {
        position: relative;
        left: 0px;
        width: 100%;
        padding-top: 5px;
      }
    }
  }

  .welcome_bg {
    padding: 0px;
    overflow: hidden;

    img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      filter: brightness(20%);
    }

    .welcome_text {
      position: absolute;
      padding: 48px;

      @media(max-width: 500px) {
        padding: 20px;
      }
    }
  }

  .welcome_video {
    padding: 25px;
    justify-content: space-between;

    video {
      height: auto;
      width: 100%;
      max-width: 548px;
      border-radius: .75rem;
    }

    @media(max-width: 500px) {
      gap: 10px;
      padding: 20px;
    }

    .welcome_left {
      height: 100%;
      width: 50%;
      @media(max-width: 500px) {
        width: 100%;
        text-align: center;
        video {
          width: 325px;
          max-width: 100%;
        }
      }
    }

    .welcome_right {
      height: 100%;
      width: 48%;
      @media(max-width: 500px) {
        width: 100%;
      }
    }

  }

  @keyframes movingBlink {
        0% {
            left: -40px;
        }
        75%, 100% {
            left: calc(100% + 40px);
        }
    }

  .btn-green-outline {
    padding: 8px 19px;
    background: rgb(5 46 22);
    border-radius: .75rem;
    color: #ffffff;
    box-shadow: inset 0 0 #fff, inset 0 0 0 1px #00ec66, 0 1px 2px #0000000d;
    display: block;
    width: fit-content;
    font-weight: bold;
    margin-bottom: 70px;
    @media(max-width: 500px) {
      margin-bottom: 45px;
    }

  }

  .home-head {
    display: flex;
  }
</style>
