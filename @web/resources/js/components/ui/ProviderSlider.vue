<template>
  <div class="home-head">
    <div class="slider provider-slider">
      <div class="glide" id="provider-slider">
        <div class="glide__track" data-glide-el="track">
          <ul class="glide__slides">
            <li class="glide__slide">
              <router-link to="/casino/game/provider/BOOONGO">
                <img src="/img/misc/booongo.png" width="125" alt="">
              </router-link>
            </li>
            <li class="glide__slide">
              <router-link to="/casino/game/provider/PRAGMATIC">
                <img src="/img/misc/pragmatic.png" width="140" alt="">
              </router-link>
            </li>
            <li class="glide__slide">
              <router-link to="/casino/game/provider/REELKINGDOM">
                <img src="/img/misc/reel-kingdom.png" width="160" alt="">
              </router-link>
            </li>
            <li class="glide__slide">
              <router-link to="/casino/game/provider/PLAYSON">
                <img src="/img/misc/playson.png" width="145" alt="">
              </router-link>
            </li>
            <li class="glide__slide">
              <router-link to="/casino/game/provider/EZUGI">
                <img src="/img/misc/ezugi.png" width="105" alt="">
              </router-link>
            </li>
            <li class="glide__slide">
              <router-link to="/casino/game/provider/EVOLUTION">
                <img src="/img/misc/evolution.png" width="180" alt="">
              </router-link>
            </li>
            <li class="glide__slide">
              <router-link to="/casino/game/provider/TOPTREND">
                <img src="/img/misc/ttg.png" width="155" alt="">
              </router-link>
            </li>
            <li class="glide__slide">
              <router-link to="/casino/game/provider/PRAGMATICLIVE">
                <img src="/img/misc/pragmatic-live.png" width="140" alt="">
              </router-link>
            </li>
            <li class="glide__slide">
              <router-link to="/casino/game/provider/CQ9">
                <img src="/img/misc/cq9.png" width="100" alt="">
              </router-link>
            </li>
            <li class="glide__slide">
              <router-link to="/casino/game/provider/HABANERO">
                <img src="/img/misc/habanero.png" width="160" alt="">
              </router-link>
            </li>
            <li class="glide__slide">
              <router-link to="/casino/game/provider/TOPTREND">
                <img src="/img/misc/pg.png" width="160" alt="">
              </router-link>
            </li>
          </ul>
        </div>
        <div class="glide__arrows" data-glide-el="controls">
          <button class="glide__arrow glide__arrow--left" data-glide-dir="<"></button>
          <button class="glide__arrow glide__arrow--right" data-glide-dir=">"></button>
        </div>
        <!--
        <div class="glide__bullets" data-glide-el="controls[nav]">
          <button class="glide__bullet" data-glide-dir="=0"></button>
          <button class="glide__bullet" data-glide-dir="=1"></button>
          <button class="glide__bullet" data-glide-dir="=2"></button>
        </div>
        -->
      </div>
    </div>
  </div>
</template>

<script>
  import Glide from '@glidejs/glide';

  export default {
    mounted() {
      this.initSlider();
    },
    methods: {
      initSlider() {
        this.$nextTick(() => {
          new Glide('#provider-slider', {
            type: 'carousel',
            perView: 5,
            autoplay: 2500,
            //autoplay: false, // Disable autoplay
            hoverpause: true, 
            keyboard: false,

            breakpoints: {
              1024: {
                perView: 4
              },
              768: {
                perView: 3
              },
              640: {
                perView: 3
              },
              320: {
                perView: 2
              }
            }

          }).mount();
        });
      },

    }
  }
</script>

<style scoped lang="scss">
  .provider-slider {
    height: 80px !important;
    margin: 50px 0px;
    padding: 0px 50px;
    @media(max-width: 991px) {
      height: 60px !important;
    }

    .slider {
      height: auto;
    }
    img {
      max-width: 100%;
    }
    .glide__slide {
      text-align: center;
      padding: 0px !important;
      display: flex;
      justify-content: center;
      align-items: center;
      background-color: rgb(28,25,23);
      box-shadow: inset 0 0 #fff, inset 0 0 0 1px #44403c, 0 1px 2px #0000000d;
      border-radius: .75rem;
      padding: 0px 40px;
      height: auto !important;
      transition: all .3s ease;
      &:hover {
        background-color: #292524;
      }
      a {
        padding: 10px;
        display: block;
      }
    }
    .glide__arrow--left {
      left: -40px;
    }
    .glide__arrow--right {
      right: -40px;
    }
    @media(max-width: 991px) {
      padding: 0 25px;
      .slider .glide .glide__arrow--left, .slider .glide .glide__arrow--right {
        width: 25px !important;
        height: 25px !important;
      }
      .glide__arrow--left {
        left: -30px;
      }
      .glide__arrow--right {
        right: -30px;
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
  }

  .home-head {
    display: flex;
  }
</style>
