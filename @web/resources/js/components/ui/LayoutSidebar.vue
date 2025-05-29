<template>
  <div :class="'sidebar ' + (sidebar || mobileToggle ? 'visible' : 'hidden') + ' ' + (mobileToggle ? 'mobileToggle' : '')">
    <!--@click="mobileToggle = false" v-click-outside="closeSidebar"-->
    <div class="mobileToggle-bg" @click="mobileToggle = false"></div>
    <div class="fixed">
      <div class="sidebar-header">
        <div class="toggle" @click="$store.dispatch('toggleSidebar')">
          <web-icon :icon="sidebar ? 'fal fa-chevron-left' : 'fal fa-chevron-right'"></web-icon>
        </div>
        <div class="logo" @click="$router.push('/')"></div>
      </div>
      <overlay-scrollbars :options="{ scrollbars: { autoHide: 'leave' }, className: 'os-theme-thin-light' }"
                          :class="`games ${!isCasino ? 'sportSidebar' : ''}`">
        <template v-if="isCasino">
          <router-link tag="div" to="/games" class="game">
            <img src="/img/misc/home.svg" alt="">
            <div>{{ $t('general.sidebar.all_games') }}</div>
          </router-link>

          <router-link tag="div" to="/vip" class="game">
            <img src="/img/misc/vip.svg" alt="">
            <div>VIP</div>
          </router-link>

          <template v-if="$isAvailable('phoenixSport')">
            <div class="sidebar-description">{{ $t('general.head.sport') }}</div>

            <router-link tag="div" to="/sport" class="game">
              <web-icon icon="sport"></web-icon>
              <div>{{ $t('general.head.sport') }}</div>
            </router-link>
          </template>


          <!--<div class="sidebar-description">Promoties</div>-->

          <div class="game" @click="isGuest ? openAuthModal('auth') : openBonusModal()">
            <img src="/img/misc/chest.svg" alt="">
            <div>Bonus</div>
          </div>

          <router-link tag="div" to="/partner" class="game">
            <img src="/img/misc/affiliates.svg" alt="">
            <div>{{ $t('footer.affiliates') }}</div>
          </router-link>

          <router-link tag="div" to="/recommended-games" class="game">
            <img src="/img/misc/top-games.svg" alt="">
            <div>{{ $t('general.sidebar.recommended') }}</div>
          </router-link>

          <router-link tag="div" to="/slot-games" class="game">
            <img src="/img/misc/slot.svg" alt="">
            <div>{{ $t('general.sidebar.slot') }}</div>
          </router-link>

          <router-link tag="div" to="/live-games" class="game">
            <img src="/img/misc/live.svg" alt="">
            <div>{{ $t('general.sidebar.live') }}</div>
          </router-link>

          <router-link tag="div" to="/original-games" class="game">
            <img src="/img/misc/original.svg" alt="">
            <div>{{ $t('general.sidebar.originals') }}</div>
          </router-link>
          
          <!--
          <div class="dropdown">
            <div class="sidebar-description dropdown-toggle" @click="openRecommendedDropdown()" :class="{ 'open': recommendedDropdownOpen }">
              <img src="/img/misc/top-games.svg" alt="">
              {{ $t('general.sidebar.recommended') }}
            </div>
            <div class="dropdown-menu" v-show="recommendedDropdownOpen">
              <router-link v-for="game in recommendedGames" :key="game.id" :to="`/casino/game/` + game.id" class="game">
                <img :src="game.image" alt="Game Image" class="game-img">
                <div>{{ game.name }}</div>
              </router-link>
            </div>
          </div>

          <div class="dropdown">
            <div class="sidebar-description dropdown-toggle" @click="openSlotsDropdown()" :class="{ 'open': slotsDropdownOpen }">
              <img src="/img/misc/slot.svg" alt="">
              {{ $t('general.sidebar.slot') }}
            </div>
            <div class="dropdown-menu" v-show="slotsDropdownOpen">
              <router-link v-for="game in slotGames" :key="game.id" :to="`/casino/game/` + game.id" class="game">
                <img :src="game.image" alt="Game Image" class="game-img">
                <div>{{ game.name }}</div>
              </router-link>
            </div>
          </div>

          <div class="dropdown">
            <div class="sidebar-description dropdown-toggle" @click="openLiveDropdown()" :class="{ 'open': liveDropdownOpen }">
              <img src="/img/misc/live.svg" alt="">
              {{ $t('general.sidebar.live') }}
            </div>
            <div class="dropdown-menu" v-show="liveDropdownOpen">
              <router-link v-for="game in liveGames" :key="game.id" :to="`/casino/game/` + game.id" class="game">
                <img :src="game.image" alt="Game Image" class="game-img">
                <div>{{ game.name }}</div>
              </router-link>
            </div>
          </div>
          -->

          <div onclick="window.location.href = '/admin'" v-if="$checkPermission('dashboard')" class="game">
              <i class="fas fa-cog"></i>
              <div>{{ $t('general.sidebar.admin') }}</div>
          </div>

          <div class="language">
            <select class="languageSelector" @change="setLanguage(language)" v-model="language">
              <option :selected="locale === 'en'" value="en">EN</option>
              <option :selected="locale === 'nl'" value="nl">NL</option>
              <option :selected="locale === 'de'" value="de">DE</option>
              <!--<option :selected="locale === 'fr'" value="fr">FR</option>
              <option :selected="locale === 'es'" value="es">ES</option>-->
            </select>
          </div>

          <!--
          <div>
            <div class="gradient-border">
              <div class="gradient-container">
                <div class="border-divider"></div>
              </div>
              <div class="gradient-divider"></div>
            </div>
          </div>-->


        </template>
        <!--
        <template v-else>
          <div :class="`game ${betSlip ? 'router-link-exact-active router-link-active' : ''}`"
               @click="$store.dispatch('toggleBetSlip')">
            <web-icon icon="fas fa-ticket-alt"></web-icon>
            <div>{{ $t('sport.bet_slip') }}</div>
          </div>

          <content-placeholders class="game" v-for="n in 17" :key="n" v-if="sportGames.length === 0">
            <content-placeholders-img/>
          </content-placeholders>
          <template v-if="sportGames && sportGames.length > 0">
            <template v-if="!isGuest && $checkPermission('dashboard')">
              <div onclick="window.location.href = '/admin'" class="game">
                <i class="fas fa-cog"></i>
                <div>{{ $t('general.sidebar.admin') }}</div>
              </div>
              <div class="divider"></div>
            </template>

            <div v-for="game in sportGames.filter(e => e.sportType === sportType)" :key="game.id"
                 class="game" @click="navigateTo(game)">
              <web-icon :icon="game.icon"></web-icon>
              <div class="liveCount" v-if="game.liveCount > 0">{{ game.liveCount }}</div>
              <div>{{ game.name }}</div>
            </div>
          </template>
        </template>-->
      </overlay-scrollbars>
    </div>
  </div>
</template>

<script>
  import {mapGetters} from 'vuex';
  import AuthModal from "../modals/AuthModal.vue";
  import Bus from "../../bus";
  import FaucetModal from "../modals/FaucetModal.vue";
  import LeaderboardModal from "../modals/LeaderboardModal.vue";

  export default {
    data() {
      return {
        promo: false,
        language: null,
        mobileToggle: false,
        tempBlock: false,
        originalsDropdownOpen: false,
        //recommendedDropdownOpen: false,
        //slotsDropdownOpen: false,
        //liveDropdownOpen: false,
      }
    },
    created() {
      this.language = this.locale;
      Bus.$on('sidebar:toggleMobile', () => {
        this.tempBlock = true;
        setTimeout(() => this.tempBlock = false, 1);
        this.mobileToggle = !this.mobileToggle;
      });
    },
    computed: {
      ...mapGetters(['isGuest', 'user', 'theme', 'games', 'currencies', 'sidebar', 'sportGames', 'betSlip', 'locale']),
      /*recommendedGames() {
        const recommendedGameIds = ["external:fc:vs20fruitsw", "external:fc:StockMarket00001", "external:fc:bigbassnewbb1320", "external:fc:vs20sugarrush", "external:fc:spacemanyxe123nh", "external:fc:vs20olympx", "external:fc:CrazyTime0000001", "external:fc:228000", "external:fc:vswaysdogs", "external:fc:vs40bigjuan", "external:fc:zixzea8nrf1675oh", "external:fc:wild-ape-3258", "external:fc:vs20wildparty", "external:fc:vswaystut", "external:fc:FunkyTime0000001", "external:fc:vswayszombcarn", "external:fc:k2oswnib7jjaaznw", "external:fc:vs20gobnudge", "external:fc:vswaysbbb", "external:fc:vswayslight", "external:fc:GoldVaultRo00001", "external:fc:vs20candyblitz", "external:fc:SweetSugar", "external:fc:vs20starlight", "external:fc:5bzl2835s5ruvweg", "external:fc:Monopoly00000001", "external:fc:md500q83g7cdefw1", "external:fc:vs20fruitparty", "external:fc:15_golden_eggs", "external:fc:r20speedrtwo201s", "external:fc:vs10starpirate", "external:fc:1hl323e1lxuqdrkr", "external:fc:vs25goldparty", "external:fc:jzbzy021lg8xy9i2", "external:fc:pbvzrfk1fyft4dwe", "external:fc:vswaysrsm"];
        return this.games.filter(game => recommendedGameIds.includes(game.id));
      },
      slotGames() {
        return this.shuffleArray(this.games.filter(game => game.category.includes('slots'))).slice(0, 20);
      },
      liveGames() {
        return this.shuffleArray(this.games.filter(game => game.category.includes('live'))).slice(0, 20);
      }*/
    },
    methods: {
      shuffleArray(array) {
        for (let i = array.length - 1; i > 0; i--) {
          const j = Math.floor(Math.random() * (i + 1));
          [array[i], array[j]] = [array[j], array[i]];
        }
        return array;
      },
      openLeaderboard() {
        LeaderboardModal.methods.open();
      },
      /*openRecommendedDropdown() {
        this.recommendedDropdownOpen = !this.recommendedDropdownOpen;
      },
      openSlotsDropdown() {
        this.slotsDropdownOpen = !this.slotsDropdownOpen;
      },
      openLiveDropdown() {
        this.liveDropdownOpen = !this.liveDropdownOpen;
      },*/
      openBonusModal() {
        //FaucetModal.methods.open();
        this.$toast.error(this.$i18n.t('faucet.timeout'));
      },
      navigateTo(sport) {
        this.$store.dispatch('setSportFilter', sport.liveCount === 0 ? 'upcoming' : 'live');
        this.$router.push('/sport/category/' + sport.id);
      },
      closeSidebar() {
        if (!this.tempBlock) this.mobileToggle = false;
      },
      openAuthModal(type) {
        AuthModal.methods.open(type);
      },
      openSearch() {
        Bus.$emit('search:toggle');
      },
      setLanguage(language) {
        this.$store.dispatch('changeLocale', language);
        //this.$store.dispatch('setChatChannel', `${this.isCasino ? 'casino' : 'sport'}_${language}`);
        //this.loadChannel();
      },
    }
  }
</script>

  <style lang="scss">
  @import "resources/sass/variables";

  .sidebar.mobileToggle {
    opacity: 1;

    .mobileToggle-bg {
      display: block;
    }

    /*
    .fixed {
      padding: 18px 0;
      padding-bottom: 120px;
    }
    */
  }

  @media(min-width: 1200px) {
    .sidebar.visible ~ .pageWrapper {
      padding-left: $sidebar-width-expand;
    }
  }

  @media(max-width: 991px) {
    .sidebar.visible ~ .pageWrapper {
      padding-left: 0 !important;;
    }
  }

  @media(min-width: 1200px), (max-width: 991px) {
    /*.sidebar .games {
      height: calc(100% - 95px) !important;
    }*/

    .sidebar .sidebar-header {
      display: flex !important;
      justify-content: center;
    }

    .sidebar {
      width: -$sidebar-width-expand;
    }

    .sidebar.visible {
      width: $sidebar-width-expand;
      left: 0px;
      //transition: 0.2s ease;

      .sidebar-description {
        opacity: 1;
        margin-left: 0px;
        height: auto;
        display: block;
      }

      .divider {
        margin-top: 35px !important;
      }

      &.mobileToggle {
        width: 0px;
        left: -$sidebar-width-expand;
      }

      .fixed {
        width: $sidebar-width-expand;

        .language {
          display: block;
          opacity: 1;
        }

        .game {
          justify-content: unset;
          position: relative;
          white-space: nowrap;

          .liveCount {
            @include themed() {
              background: t('secondary');
              color: black;
              width: 15px;
              height: 15px;
              border-radius: 50%;
              display: flex;
              align-items: center;
              justify-content: center;
              margin-left: 10px;
              font-size: .6em;
              font-weight: 600;
              position: absolute;
              top: 8px;
            }
          }

          i {
            width: 25px;
          }

          img {
            width: 25px;
            height: auto;
            margin-right: 5px;
          }

          svg {
            margin-right: 11px;
          }

          div {
            display: block;
            opacity: 1;
            overflow: hidden;
            text-overflow: ellipsis;
          }
         
        }

        .dropdown {
          position: relative;
          display: inline-block;
          width: 100%;
        }

        .dropdown-toggle {
          cursor: pointer;
        }

        .sidebar-description img {
          width: 25px;
          height: auto;
          margin-right: 5px;
        }

        .dropdown-menu {
          display: none;
          position: relative;
          top: 100%;
          left: 0;
          box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
          z-index: 1;
          background: rgb(28, 25, 23);
          border-radius: .75rem;
          color: #78716c;
          box-shadow: inset 0 0 #fff, inset 0 0 0 1px #44403c, 0 1px 2px #0000000d;
          padding: 10px 0px !important;
          .game {
            padding: 5px 10px;
            gap: 6px;
            height: auto;
            img {
              width: 42px;
              height: 38px;
            }
          }
        }

        .dropdown-toggle.open + .dropdown-menu {
          display: block;
        }
      }

      .promotion {
        height: unset;

        .name, .description {
          opacity: 1;
          transition-delay: .4s;
          display: block;
        }

        .image {
          position: unset;
          top: unset;
          left: unset;
        }
      }
    }
  }

  .sidebar {
    width: $sidebar-width;
    flex-shrink: 0;
    z-index: 38002;
    transition: width 0.3s ease;

    .mobileToggle-bg {
      display: none;
      position: fixed;
      left: 0px;
      top: 0px;
      width: 100%;
      height: 100%;
      background: rgba(41, 37, 36, .75);
    }

    .os-scrollbar-horizontal {
      display: none;
    }

    .os-content {
      padding: 20px !important;
      @media(max-width: 1200px) {
        padding: 20px 15px!important;
      }
    }

    .sidebar-description {
      left: 0;
      height: 0;
      //transition: height .3s ease, opacity .3s ease, margin-left .3s ease;
      opacity: 0;
      padding: 10px 0px;
      display: none;
      @include themed() {
          color: t('link');

          
          &.featured {
            color: rgb(0, 220, 130);
          }
      }
    }

    .promotion {
      margin-top: 20px;
      background: linear-gradient(274.28deg, rgba(255, 195, 76, 0) 0%, rgb(25 32 46) 100%), #20293c;
      padding: 20px 40px;
      display: flex;
      align-items: center;
      cursor: pointer;
      position: relative;
      height: 90px;
      transition: height .3s ease;

      .image {
        width: 40px;
        height: 40px;
        background: url('/img/misc/treasure.png') no-repeat center;
        background-size: cover;
        margin-right: 20px;
        position: absolute;
        top: 25px;
        left: 20px;
      }

      .name, .description {
        transition: opacity .3s ease;
        opacity: 0;
        transition-delay: 0s;
        display: none;
      }

      .name {
        color: #FFC34C;
        text-shadow: 0 0 8px rgba(255, 195, 76, 0.62);
        font-size: 16.5px;
      }

      .description {
        margin-top: 2px;
        font-weight: 500;
        font-size: 14.5px;
      }
    }

    .fixed {
      position: fixed;
      top: 0;
      left: 0;
      width: $sidebar-width;
      height: 100%;
      //transition: 0.5s ease;

      @include themed() {
        //box-shadow: 0 0 12px rgba(0, 0, 0, 0.56);
        //background: t('sidebar');
        //transition: background 0.15s ease, width .3s ease;

        .sidebar-header {
          height: $header-height;
          background: rgba(28, 25, 23, .75);
          backdrop-filter: blur(10px);
          border-bottom: 1px solid rgba(41, 37, 36);
          display: none;

          .logo {
            background: url('/img/misc/logo.png') no-repeat center;
            width: 175px;
            height: 100%;
            background-size: contain;
            margin-top: auto;
            margin-bottom: auto;
            margin-left: 5px;
            margin-right: auto;
            display: none;
            cursor: pointer;
          }

          @media(max-width: 991px) {
            display: none !important;
          }

          .toggle {
            display: flex;
            padding: 10px 14px;
            background: t('chat-accent');
            margin: auto;
            cursor: pointer;
            transition: background .3s ease;
            border-radius: .75rem;
            box-shadow: inset 0 0 #fff, inset 0 0 0 1px #44403c, 0 1px 2px #0000000d;

            &:hover {
              background: t('header-block');
            }
          }
        }

        .games {
          height: 100%;
          //background-color: rgb(41, 37, 36);
          background: rgba(28, 25, 23, .75);
          backdrop-filter: blur(10px);
          margin: 12px 10px;
          color: #78716c;
          box-shadow: inset 0 0 #fff, inset 0 0 0 1px #44403c, 0 1px 2px #0000000d;
          height: calc(100% - 88px);
          border-radius: .75rem;

          @media(max-width: 1200px) {
            margin: 0px;
            height: 100%;
            border-radius: 0px;
            box-shadow: none;
            background: rgb(28 25 23);
          }


          &.sportSidebar {
            height: calc(100% - 35px);
          }

          .divider {
            margin-top: 5px;
            transition: margin-top .3s ease;
          }

          .btn {
            width: calc(100% - 30px);
            margin-left: 15px;
            margin-right: 15px;
            margin-bottom: 15px;
            border-radius: 20px;

            &.btn-primary {
              border-bottom: 3px solid darken(t('secondary'), 5%);
            }

            &.btn-secondary {
              border-bottom: 3px solid darken($gray-600, 5%);
            }
          }
        }
      }

      .language {
        display: none;
        opacity: 0;
        transition: opacity 1s ease;
      }

      .game {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        cursor: pointer;
        position: relative;
        transition: color .3s ease;
        padding: 10px 0;
        @include themed() {
          color: t('link');

          &:hover {
            color: #fff;
          }

          &.featured {
            color: rgb(0, 220, 130);
          }
        }

        @include themed() {
          &.highlight {
            color: t('secondary') !important;
          }
        }

        div {
          display: none;
          opacity: 0;
          transition: opacity 1s ease;
        }

        .vue-content-placeholders-img {
          display: block !important;
          opacity: 1 !important;
        }

        .vue-content-placeholders-img {
          height: 15px;
          width: 15px;
          border-radius: 3px;
        }

        img {
          width: 14px;
          height: 14px;
        }

        i {
          cursor: pointer;
        }

        &:hover {
          opacity: 1;
        }

        .online {
          position: absolute !important;
          top: 4px !important;
          left: 17px !important;
          border-radius: 50%;
          width: 15px;
          @include themed() {
            background: t('secondary');
          }
          color: white;
          height: 15px;
          font-size: 0.5em;
          display: flex;
          align-items: center;
          justify-content: center;
          text-align: center;
        }
      }

      .game.router-link-exact-active {
        opacity: 1;
      }
    }

    &.visible .fixed .sidebar-header {
      .logo {
        display: unset;
      }

      .toggle {
        margin-left: 10px !important;
        margin-right: 10px !important;
      }
    }
  }

  @media(max-width: 991px) {
    .sidebar.visible .fixed {
      left: -$sidebar-width-expand;
      transition: .5s ease;
    }

    .sidebar.mobileToggle .fixed {
      left: 0px !important;
    }
  }
</style>
