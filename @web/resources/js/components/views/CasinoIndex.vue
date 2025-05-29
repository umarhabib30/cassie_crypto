<template>
  <div class="container">
    <index-slider></index-slider>

    <game-category-search :style="{ marginTop: '20px' }"></game-category-search>

    <!---
    <div v-if="isGuest" class="glide" id="banner-card-slider">
      <div class="glide__track" data-glide-el="track">
          <ul class="glide__slides">
            <li class="glide__slide">

      <div class="banner_card welcome" @click="handleButtonClick">
          <div class="layer layer_0"></div>
          <div class="layer layer_grid"></div>
          <div class="layer layer_3">
              <div class="back_side">
                  <h2 class="title">{{$t('general.banner_card.welcome_title')}}</h2>
                  <p class="subtitle">{{$t('general.banner_card.welcome_subtitle')}}</p>
              </div>
              <div class="face_side">
                  <img class="card_img welcome" src="/img/misc/welcome.png" draggable="false">
                  <div class="card_text">
                      <h2 class="title">{{$t('general.banner_card.welcome_title')}}</h2>
                      <p class="subtitle">{{$t('general.banner_card.welcome_subtitle')}}</p>
                  </div>
              </div>
          </div>
      </div>
    </li>

    <li class="glide__slide">

      <div class="banner_card no_verification" @click="handleButtonClick">
          <div class="layer layer_0"></div>
          <div class="layer layer_grid"></div>
          <div class="layer layer_3">
              <div class="back_side">
                  <h2 class="title">{{$t('general.banner_card.no_verification_title')}}</h2>
                  <p class="subtitle">{{$t('general.banner_card.no_verification_subtitle')}}</p>
              </div>
              <div class="face_side">
                  <img class="card_img no_verification" src="/img/misc/no-verification.png" draggable="false">
                  <div class="card_text">
                      <h2 class="title">{{$t('general.banner_card.no_verification_title')}}</h2>
                      <p class="subtitle">{{$t('general.banner_card.no_verification_subtitle')}}</p>
                  </div>
              </div>
          </div>
      </div>
    </li>

    <li class="glide__slide">
      <div class="banner_card fast_payouts" @click="handleButtonClick">
          <div class="layer layer_0"></div>
          <div class="layer layer_grid"></div>
          <div class="layer layer_3">
              <div class="back_side">
                  <h2 class="title">{{$t('general.banner_card.fast_payouts_title')}}</h2>
                  <p class="subtitle">{{$t('general.banner_card.fast_payouts_subtitle')}}</p>
              </div>
              <div class="face_side">
                  <img class="card_img fast_payouts" src="/img/misc/fast-payouts.png" draggable="false">
                  <div class="card_text">
                      <h2 class="title">{{$t('general.banner_card.fast_payouts_title')}}</h2>
                      <p class="subtitle">{{$t('general.banner_card.fast_payouts_subtitle')}}</p>
                  </div>
              </div>
          </div>
      </div>
    </li>
  </ul>
</div>
    </div>
    --->

    <div class="crypto-coins">
      <div class="crypto-coins-wrap">
        <div class="coins">
          <img src="/img/misc/bitcoin.svg" width="40" alt="">
          <img src="/img/misc/bitcoin-cash.svg" width="40" alt="">
          <img src="/img/misc/ethereum.svg" width="40" alt="">
          <img src="/img/misc/litecoin.svg" width="40" alt="">
          <img src="/img/misc/usdc.svg" width="40" alt="">
          <img src="/img/misc/doge.svg" width="40" alt="">
        </div>
        
        <img src="/img/misc/ideal.png" width="40" alt="">
        <img src="/img/misc/visa.svg" width="40" alt="">
        <img src="/img/misc/mastercard.svg" width="40" alt="">
        <img src="/img/misc/maestro.svg" width="40" alt="">
        <img src="/img/misc/apple-pay.svg" width="40" alt="">
        <img src="/img/misc/google-pay.svg" width="40" alt="">
        <img src="/img/misc/moonpay.png" width="70" alt="">
      </div>
      <div class="gradient-border">
        <div class="gradient-container">
          <div class="border-divider"></div>
        </div>
        <div class="gradient-divider home-divider"></div>
      </div>
    </div>

   <!--
    <div class="buy-crypto-section">
      <div>
        <h3>{{$t('general.nocrypto')}}</h3>
        <button class="btn btn-green" @click="openAuthModal('register')">{{$t('general.buy_now')}}</button>
      </div>
      <div class="buy-crypto-coins">
        <img src="/img/misc/bitcoin.svg" width="40" alt="">
        <img src="/img/misc/bitcoin-cash.svg" width="40" alt="">
        <img src="/img/misc/ethereum.svg" width="40" alt="">
        <img src="/img/misc/litecoin.svg" width="40" alt="">
        <img src="/img/misc/tether-usdt.svg" width="40" alt="">
        <img src="/img/misc/doge.svg" width="40" alt="">
      </div>
    </div>
    -->

    <recommend-game-list></recommend-game-list>


    <provider-slider></provider-slider>

    <slot-game-list></slot-game-list>
    <live-game-list></live-game-list>
    <original-game-list></original-game-list>

    <!--
    <popular-game-list></popular-game-list>
    -->

    <!--
    <games :isIndex="true"></games>
    -->

    <div class="provider-games">
      <router-link to="/games" class="btn">{{ $t('general.provider_games') }}</router-link>
    </div>
    
  </div>
</template>

<script>
  import {mapGetters} from 'vuex';
  import AuthModal from "../modals/AuthModal.vue";
  import PasswordResetModal from "../modals/PasswordResetModal.vue";
  //import Games from "../ui/GameList.vue";
  import RecommendGameList from "../ui/RecommendGameList.vue";
  import SlotGameList from "../ui/SlotGameList.vue";
  import LiveGameList from "../ui/LiveGameList.vue";
  import OriginalGameList from "../ui/OriginalGameList.vue";
  //import PopularGameList from "../ui/PopularGameList.vue";
  import IndexSlider from "../ui/IndexSlider.vue";
  import ProviderSlider from "../ui/ProviderSlider.vue";
  import GameCategorySearch from "../ui/GameCategorySearch.vue";

  import WalletModal from "../modals/WalletModal.vue";
  import Glide from '@glidejs/glide';

  export default {
    computed: {
      ...mapGetters(['games', 'isGuest'])
    },
    mounted() {
    this.initSlider();
    },
    methods: {
      initSlider() {
      this.$nextTick(() => {
        new Glide('#banner-card-slider', {
          type: 'carousel',
          perView: 3,
          hoverpause: true,
          draggable: false,
          autoplay: false,
          breakpoints: {
            890: {
              focusAt: 'center',
              perView: 1,
              autoplay: 2500,
              draggable: true,
              peek: { before: 50, after: 50 },
              gap: 20
            }
          }
        }).mount();
      });
      },
      openFaucetModal() {
        this.$router.push('/bonus');
      },
      openAuthModal(type) {
        AuthModal.methods.open(type);
      },
      openWalletModal() {
        WalletModal.methods.open();
      },
      handleButtonClick() {
        if (this.isGuest) {
          this.openAuthModal('register');
        } else {
          this.openWalletModal();
        }
      }
    },
    created() {
      if (this.$route.params.user && this.$route.params.token)
        PasswordResetModal.methods.open(this.$route.params.user, this.$route.params.token);
      else if(this.$route.params.token) {
        axios.post('/auth/confirmEmail', {
            token: this.$route.params.token,
        }).then(() => {
            this.$toast.success(this.$i18n.t('general.profile.email_confirmed'));
        }).catch(() => {
            this.$toast.error(this.$i18n.t('general.profile.invalid_token'))
        });
      }
    },
    components: {
      GameCategorySearch,
      //Games,
      IndexSlider,
      ProviderSlider,
      RecommendGameList,
      SlotGameList,
      LiveGameList,
      OriginalGameList


      //PopularGameList
    }
  }
</script>

<style lang="scss" scoped>
  @import "resources/sass/variables";

  .slotExplorerIndex {
    .games {
      justify-content: unset;

      @media(max-width: 1610px) {
        justify-content: center;
      }
    }

    .game_poster {
      width: calc(20% - 14px) !important;
      height: 225px !important;
      margin: 7px !important;

      @media(max-width: 1500px) {
        width: calc(25% - 14px) !important;
      }

      @media(max-width: 991px) {
        width: 155px !important;
        height: 155px !important;
        margin: 3px !important;
      }
    }

    .text .name {
      display: none;
    }

    .index_cat {
      margin-left: 0;
      margin-bottom: 15px;
    }
  }

  .index_cat_banner {
    display: flex;

    .slots {
      background: url('/img/misc/index_slots.png') no-repeat left;
    }

    .sports {
      background: url('/img/misc/sports.png') no-repeat left;
    }

    .live_games_unavailable {
      background: rgba(255, 255, 255, .025);
      border-radius: 12px;
      width: 50%;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;

      i, svg {
        font-size: 2.5em;
        margin-bottom: 15px;
      }
    }

    .slots, .sports {
      width: 50%;
      background-size: cover;
      height: 200px;
      margin: 5px;
      cursor: pointer;
      border-radius: 12px;
      transition: all .3s ease;

      &:hover {
        transform: scale(1.01);
      }
    }

    @media(max-width: 991px) {
      .slots, .sports {
        margin: 0;
      }

      .slots {
        margin-bottom: 10px;
      }
    }
  }

  .indexCategories {
    height: 75px;
    margin-bottom: 40px;

    .os-host {
      flex: 1;
      width: 0;

      .os-content {
        display: flex;
      }
    }

    @include themed() {
      display: flex;
      padding-left: 40px;
      padding-right: 40px;
      background: t('sidebar');

      .category {
        cursor: pointer;
        transition: color .3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 25px 20px;
        white-space: nowrap;

        svg, i {
          margin-right: 10px;
        }

        &:hover {
          color: t('secondary');
        }

        &.active {
          color: t('secondary');
        }
      }
    }
  }

  @media(max-width: 450px) {
    .indexCategories {
      padding-left: 10px !important;
      padding-right: 10px !important;
    }
  }












.crypto-coins {
  margin: 50px 0px;
}

.crypto-coins .gradient-border {
    position: relative;
    width: 95%;
    margin: auto;
    margin-top: -2px;
}

.crypto-coins .crypto-coins-wrap {
  padding: 15px;
  background: rgb(28, 25, 23);
  border-radius: .75rem;
  color: #78716c;
  box-shadow: inset 0 0 #fff, inset 0 0 0 1px #44403c, 0 1px 2px #0000000d;
  display: flex;
  justify-content: center;
  align-items: center;
  flex-wrap: wrap;
  gap: 10px;

  .coins {
    display: flex;
  }

  .coins img:not(:first-of-type) {
    margin-left: -17px;
  }
}










#banner-card-slider {
  margin: 25px 0px 0px;
  overflow: hidden;

.glide__track {
  overflow: visible !important;
}

.glide__slides {
  display: flex;
  flex-wrap: nowrap;
}

.glide__slide {
  flex: 0 0 auto;
  width: calc(100% / 3);
  transition: transform 0.3s ease-in-out;
}

@media (max-width: 890px) {
  .glide__slide {
    width: 80%;
    margin: 0 10px;
  }
}
}

.banner_card {
    display: flex;
    gap: 0;
    height: 156px;
    overflow: visible;
    position: relative;
    transition: all .5s cubic-bezier(.34,1.56,.64,1);
    width: 100%;
    cursor: pointer;
    margin-top: 25px;
    margin-bottom: 2px;
}

.banner_card .mobile {
    overflow: hidden
}

.banner_card .layer {
    background-repeat: no-repeat;
    background-size: contain;
    border-radius: .75rem;
    //display: flex;
    height: 100%;
    left: 0;
    position: absolute;
    right: 0;
    top: 0;
    width: 100%
}

.banner_card .layer .back_side,.banner_card .layer .face_side {
    display: flex;
    flex-direction: column;
    z-index: 10
}

.banner_card .layer .back_side .card_text,.banner_card .layer .face_side .card_text {
    display: flex;
    flex-direction: column;
    padding: 0px 20px;
}

.banner_card .layer .back_side {
    align-items: flex-start;
    height: 86%;
    left: 16px;
    opacity: 0;
    position: absolute;
    right: 16px;
    top: 16px;
    z-index: 20
}

.banner_card .layer .back_side .subtitle {
    text-align: left
}

.banner_card .layer .back_side .btn_link {
    bottom: 8px;
    height: 20px;
    position: absolute;
    right: 4px;
    width: 20px
}

.banner_card .layer .back_side .btn_link:hover {
    cursor: pointer
}

.banner_card .layer_grid {
    background-image: url('/img/misc/border.png');
    background-size: 20px;
    background-repeat: round;
    z-index: 2
}

.banner_card .layer_0 {
    //background: radial-gradient(42.48% 78.68% at 13.31% 17.32%,rgba(41, 229, 113, 0) 0,rgba(19, 177, 111, 0) 98.5%),linear-gradient(144deg,#00491c 4.75%,#002710 17.07%,#000c03 98.32%);
    //border: 1px solid #008521;
    //box-shadow: inset 0 0 34px #00853766;
    transition: background .5s cubic-bezier(.34,1.56,.64,1),box-shadow .5s cubic-bezier(.34,1.56,.64,1);
    z-index: 1;

    background-color: rgb(28,25,23);
    /* background-image: linear-gradient(150deg, #292524, #524e4d); */
    box-shadow: inset 0 0 #fff, inset 0 0 0 1px #44403c, 0 1px 2px #0000000d;
}

.banner_card .layer_border_1 {
    opacity: 0;
    transition: opacity .5s cubic-bezier(.34,1.56,.64,1)
}

.banner_card .layer_border_1 {
    border: 1px solid #fc8235;
    filter: blur(4px)
}

.banner_card .back_side,.banner_card .face_side .card_img,.banner_card .face_side .card_text {
    transition: transform .5s cubic-bezier(.34,1.56,.64,1),opacity .5s cubic-bezier(.34,1.56,.64,1)
}

.banner_card .back_side {
    opacity: 0;
    transform: translateY(-20px)
}

.banner_card .back_side .card_img.welcome {
    bottom: -2px;
    height: 146px;
    left: unset;
    right: -11px;
    transform: unset;
    width: 170px
}

.banner_card .back_side .card_img {
    bottom: -24px;
    top: unset;
    z-index: 10
}

@media screen and (min-width: 768px) {
    .banner_card .back_side .card_img {
        height:100px;
        width: 105%
    }
}

.banner_card.mobile .title {
    font-size: 12px;
    z-index: 100
}

.banner_card.mobile .subtitle {
    font-size: 16px;
    line-height: 20px;
    z-index: 100
}

.banner_card.mobile .back_side {
    opacity: 1;
    transform: translateY(0)
}

.banner_card:hover .layer_border_1,.banner_card:hover {
    opacity: 1
}

.banner_card:hover .layer_border_1 {
    z-index: 10
}

.banner_card:hover .face_side .card_img {
    opacity: 0;
    transform: scale(.7) translate(-70%) translateY(-20px)
}

.banner_card:hover .face_side .card_text {
    opacity: 0;
    transform: translateY(-20px)
}

.banner_card:hover .back_side {
    opacity: 1;
    transform: translateY(0)
}

.banner_card .layer_3 {
    z-index: 99;
    padding-top: 60px;
}

.banner_card .card_img {
    height: 80px;
    left: 50%;
    pointer-events: none;
    position: absolute;
    right: 0;
    top: -28px;
    transform: translate(-50%);
    max-width: 100%;
  }

.banner_card.mobile .card_img {
    left: unset;
    transform: unset
}

.banner_card .subtitle,.banner_card .title {
    color: #fff;
    overflow: hidden;
    text-align: center;
    text-overflow: ellipsis
}

.banner_card .title {
    font-optical-sizing: auto;
    margin: 0 0 4px;
    text-align: left;
    font-size:17px;
    text-align: center;
    margin-bottom: 8px;
}

.banner_card .subtitle {
    //line-height: 26px;
    font-size:14px;
    line-height: 128%;
    width: 100%
}

.banner_card.mobile.cashback .card_img {
    height: 149px;
    right: -12px;
    top: -12px
}

.banner_card.mobile.fortune .card_img {
    bottom: -5px;
    height: 156px;
    right: -12px;
    width: 196px
}

/*
 .banner_card .layer_0 {
    background: radial-gradient(42.48% 78.68% at 13.31% 17.32%,rgba(49, 255, 118, 0) 0,rgba(75, 255, 120, 0) 98.5%),linear-gradient(144deg,#004916 4.75%,#00330f 17.07%,#000c02 98.32%);
    border: 1px solid #4bff69;
    box-shadow: inset 0 0 34px #5fffa266
}*/

 .banner_card .layer_border_1 {
    border: 1px solid #fc8235;
    filter: blur(4px)
}

/*
.banner_card:hover .layer_0 {
  background: radial-gradient(42.48% 78.68% at 13.31% 17.32%, rgb(244 131 54 / 12%) 0, rgba(255, 181, 75, 0) 98.5%), linear-gradient(144deg, rgb(73 47 0 / 29%) 4.75%, rgb(51 28 0 / 57%) 17.07%, rgb(12 8 0 / 31%) 98.32%);
  //background: radial-gradient(42.48% 78.68% at 13.31% 17.32%,rgba(49, 255, 83, 0.2) 0,rgba(75, 255, 165, 0) 98.5%),linear-gradient(144deg,#00491c 4.75%,#00330f 17.07%,#000c05 98.32%)
}*/

@media screen and (max-width: 1070px) {
  .banner_card {
    height: 170px;
  }
}

@media screen and (max-width: 870px) {
  .banner_card {
    height: 140px;
  }

  .item_list {
    flex-direction: column;
    gap: 40px !important;
  }

  .subtitle {
    margin-bottom: 17px !important;
  }
}

@media screen and (max-width: 560px) {
  .banner_card {
    height: 180px;
  }
}






















.title_gradient {
    background: linear-gradient(0deg,rgba(3, 17, 6, 0),#031109);
    height: 56px;
    left: 0;
    position: absolute;
    right: 0;
    top: 0
}

.title {
    color:#fff;
    margin: 24px 0 20px;
    text-align: center
}

@media screen and (min-width: 768px) {
    .title {
        font-size: 84px;
        font-weight: 700;
        line-height: 104%;
        margin: 60px 0 20px;
        padding: 0
    }
}

.subtitle {
    color: var(--color_text_secondary,#c9c7d1);
    font-optical-sizing: auto;
    margin-bottom: 20px;
    padding: 0;
    text-align: center
}

.item_list {
    align-items: flex-start;
    display: flex;
    gap: 16px;
    width: 100%;
    margin: 80px 0;
}

.top_banner_mobile {
    display: flex;
    flex-direction: column;
    position: relative
}

.top_banner_mobile .carousel .carousel__viewport {
    height: 100%;
    padding: 4px 0
}

.top_banner_mobile .carousel .carousel__viewport .carousel__track {
    gap: 8px;
    height: 100%
}

.top_banner_mobile .carousel .carousel__viewport .carousel__slide--active .banner_card .layer_0 {
    background: radial-gradient(42.48% 78.68% at 13.31% 17.32%,rgba(49, 255, 59, 0.2) 0,rgba(75, 255, 129, 0) 98.5%),linear-gradient(144deg,#00491c 4.75%,#00330b 17.07%,#000c04 98.32%)
}

.top_banner_mobile .carousel .carousel__viewport .carousel__slide--active .banner_card .layer_border_1,.top_banner_mobile .carousel .carousel__viewport .carousel__slide--active .banner_card .layer_border_2,.top_banner_mobile .carousel .carousel__viewport .carousel__slide--active .banner_card .layer_border_3,.top_banner_mobile .carousel .carousel__viewport .carousel__slide--active .banner_card .layer_border_4 {
    opacity: 1
}

.top_banner_mobile .carousel .carousel__viewport .carousel__slide--active .banner_card .layer_border_1 {
    z-index: 10
}

.top_banner_mobile .carousel .carousel__pagination {
    bottom: 16px;
    gap: 4px;
    justify-content: center;
    left: 32px;
    margin: 26px 0 0;
    padding-left: .5rem;
    position: absolute
}

.top_banner_mobile .carousel .carousel__pagination-button {
    border: 1px solid var(--color_primary_bg,#db0037);
    border-radius: 10px;
    height: 8px;
    padding: 0;
    width: 8px
}

.top_banner_mobile .carousel .carousel__pagination-button.carousel__pagination-button--active {
    background: var(--color_primary_bg,#db0037);
    border: 1px solid var(--color_primary_bg,#db0037);
    border-radius: 10px;
    height: 8px;
    padding: 0;
    width: 32px
}

.top_banner_mobile .carousel .carousel__pagination-button:after {
    background-color: transparent
}


</style>
