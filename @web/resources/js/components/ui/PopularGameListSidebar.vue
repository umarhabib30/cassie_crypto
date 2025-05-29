<template>
  <div v-if="games" class="game-list-sidebar">
    <template>
      <div class="category">
        <div></div>
        <div class="arrows">
          <div class="arrow" @click="findPage('popular').current > 0 ? updatePage(Object.assign(findPage('popular'), { current: findPage('popular').current - 1 })) : null"
               :class="findPage('popular').current <= 0 ? 'disabled' : ''"><web-icon icon="fal fa-chevron-left"></web-icon></div>
          <div class="arrow" @click="findPage('popular').current < findPage('popular').max ? updatePage(Object.assign(findPage('popular'), { current: findPage('popular').current + 1 })) : null"
               :class="findPage('popular').current >= findPage('popular').max ? 'disabled' : ''"><web-icon icon="fal fa-chevron-right"></web-icon></div>
        </div>
      </div>
      <div class="category-games" :key="'popular'" :class="(!sort ? '' : 'sorted')">
        <game-list-entry-sidebar v-for="(game) in popularGames.slice(findPage('popular').current * gamesPerView, (findPage('popular').current + 1) * gamesPerView)" :key="game.id" :game="game"></game-list-entry-sidebar>
      </div>
    </template>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex';
  import GameListEntrySidebar from "./GameListEntrySidebar.vue";

  export default {
    components: {GameListEntrySidebar},
    data() {
      return {
        icons: {
          default: 'originals',
          'Slots (Originals)': 'slots',
          'Originals (Classic)': 'casino'
        },
        gamesPerView: 0,
        page: [],
        width: 0,

        dropdownProvider: null,
        popularGames: null
      }
    },
    props: {
      sort: {
        default: null,
        type: Object
      },
      isIndex: {
        type: Boolean,
        default: false
      }
    },
    watch: {
      games() {
        this.updateGames();
        this.setDefaultDropdownProvider();
      }
    },
    computed: {
      ...mapGetters(['games']),
      providers() {
        return this.games ? [ ...new Set(this.games.filter(e => !e.isHidden).map(e => e.type)) ].sort(e => e !== 'Originals' ? 0 : -1) : []
      }
    },
    mounted() {
      this.updateGamesPerView();
      window.addEventListener('resize', this.updateGamesPerView);
      this.setDefaultDropdownProvider();

      axios.post('/api/data/popularGames').then(({ data }) => {
        this.updatePage({
          id: 'popular',
          current: 0,
          max: Math.floor((data.length - 1) / this.gamesPerView)
        });

        this.popularGames = data;
      });
    },
    methods: {
      setDefaultDropdownProvider() {
        if(!this.$isAvailable('externalSlots')) return;

        const providers = this.findDropdownProviders();
        let index = 0;

        if(providers.length >= 3) index = 3;

        this.dropdownProvider = providers[index];
      },
      findDropdownProviders() {
        return this.providers;
      },
      sortGames(provider) {
        let games = null;

        if(!this.sort) games = this.games.filter(e => e.type === provider).slice(this.findPage(provider).current * this.gamesPerView, (this.findPage(provider).current + 1) * this.gamesPerView);
        else if(this.sort.type === 'tag') games = this.games.filter(e => e.type === provider && e.category.includes(this.sort.by));
        else games = this.games.filter(e => e.type === provider);

        games = games.filter(e => !e.isHidden);

        return games;
      },
      updateGamesPerView() {
        this.width = window.innerWidth;

        let prev = this.gamesPerView;
        if(window.innerWidth <= 1200) this.gamesPerView = 1;
        else this.gamesPerView = 3;

        if(prev !== this.gamesPerView) this.updateGames();
      },
      findPage(type) {
        return this.page.filter((e) => e.id === type)[0];
      },
      updatePage(object) {
        this.page = this.page.filter((e) => e.id !== object.id);
        this.page.push(object);
      },
      updateGames() {
        this.providers.forEach(providerName => {
          this.updatePage({
            id: providerName,
            current: 0,
            max: Math.floor((this.games.filter(e => e.type === providerName).length - 1) / this.gamesPerView)
          });
        });
      }
    }
  }
</script>

<style lang="scss">
  @import "resources/sass/themes";

  .game-list-sidebar {
    .category-games {
      display: flex;
      flex-wrap: wrap;
      &.Originals_Classic {
        .category-game {
          .image {
            height: 100%;
          }
        }

        .axes {
          position: absolute;
          left: 50%;
          top: 0;
          transform: translateX(-50%);
          background: rgba(black, 0.2);
          padding: 3px 21px;
          display: flex;
          align-items: center;
          font-size: .9em;
          font-weight: 600;
          backdrop-filter: blur(20px);

          div {
            width: 54px;
            height: 26px;
            background: url('/img/misc/axes-game-logo.png') no-repeat center;
            background-size: contain;
          }
        }
      }

      &.Originals {
        .category-game {
          min-height: 240px;
          flex: 1 1 calc(15% - 1px);
          max-width: calc(20% - 1px);

          @media(max-width: 1355px) {
            min-height: 140px;
          }

          @media(max-width: 1120px) {
            height: 120px;
            min-height: unset;
          }

          @media(max-width: 991px) {
            flex: 1 1 calc(33.3% - 10px);
            max-width: calc(33.3% - 10px);
            height: 33.3vw !important;
          }
        }
      }

      &.sorted {
        flex-wrap: wrap;
        justify-content: center;

        .category-game {
          width: 180px;
        }
      }

      .category-game {
        .image {
          width: 100%;
          height: 100%;
          background-size: cover;
          background-position: center;
          background-repeat: no-repeat;
          position: relative;
          will-change: border, transform;
          transition: all 300ms ease 0s;
          border-radius: 6px;
          overflow: hidden;
        }

        .gameInfo {
          padding: 10px;

          div {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            width: 160px;
            max-width: 100%;
            min-width: 100%;
          }

          div:first-child {
            font-weight: 600;
            margin-bottom: -2px;
          }

          div:last-child {
            opacity: .7;
            font-size: 12px;
          }
        }

        @include themed() {
          background-color: t('block');
        }

        height: 75px;
        margin: 5px;
        position: relative;
        max-width: 80px;
        transition: all .3s ease;
        cursor: pointer;
        flex: 1 1 calc(18% - 1px);

        @media(max-width: 991px) {
          min-width: unset !important;
          flex: 1 1 calc(33.3% - 10px);
          max-width: calc(33.3% - 10px);
        }

        &.extend {
          max-width: unset;
          flex: 366px 1;

          @media(max-width: 991px) {
            flex: 1 1 calc(100vw - 34px - 35vw);
            max-width: unset;
          }

          @media(min-width: 768px) and (max-width: 991px) {
            flex: 1 1 calc(100vw - 42px - 50vw);
          }
        }

        @media(max-width: 991px) {
          height: 180px !important;
          min-height: 180px !important;
        }

        /*
        &:hover {
          transform: scale(1.05);
        }*/
        &:hover {
          .image {
            box-shadow: rgba(51, 68, 146, 0.04) 0px 10px 13px 0px;
            transform: scale(1.03) translateZ(0px);
          }
        }

        .unavailable {
          z-index: 4;
          position: absolute;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          background: rgba(black, 0.4);
          color: white;
          border-radius: 8px;

          .slanting {
            transform: skewY(-5deg) translateY(-50%);
            padding: 25px;
            position: absolute;
            top: 50%;
            background: rgba(black, 0.25);
            backdrop-filter: blur(25px);
            text-transform: uppercase;
            font-weight: 600;
            width: 100%;

            .content {
              font-size: 15px;
              transform: skewY(5deg);
              text-align: center;
            }
          }
        }

        &:hover {
          .hover {
            opacity: 1;
          }
        }
/*
        .hover {
          position: absolute;
          z-index: 10;
          background: rgba(black, .8);
          display: flex;
          flex-direction: column;
          width: 100%;
          height: 100%;
          pointer-events: none;
          opacity: 0;
          transition: opacity .3s ease;
          //border-radius: 8px;
          backdrop-filter: blur(5px);

          .playButton {
            background: url('/img/misc/play.png') no-repeat center;
            width: 65px;
            height: 65px;
            background-size: cover;
            position: absolute;
            top: calc(50% - 15px);
            left: 50%;
            transform: translate(-50%, -50%);
          }

          .bottomInfo {
            margin-top: auto;
            text-align: center;
            text-transform: uppercase;
            margin-bottom: 10px;

            .gameName {
              font-weight: 600;
            }

            .hover-category {
              opacity: .7;
              font-size: .95em;
            }
          }
        }*/
      }
    }

    .category {
      display: flex;
      font-size: 16px;
      font-weight: 600;
      margin-bottom: 10px;
      justify-content: flex-end;

      @media(max-width: 991px) {
        font-size: 12px;
        margin-top: 15px;
        margin-bottom: 10px;
      }

      .icon {
        margin-right: 15px;
        display: flex;
        align-items: center;
      }

      .name {
        display: flex;
        align-items: center;
        font-size: 22px;
        font-weight: 100;
        margin-top: -1px;

        @media(max-width: 500px) {
          font-size: 14px;
        }

        .gameListDropdown {
          font-size: 14px !important;

          .wesContainer {
            @include themed() {
              background: t('border') !important;
            }
          }

          .exchangeList {
            @include themed() {
              background: t('body') !important;
            }
          }

          .name {
            font-size: 14px !important;
          }
        }
      }

      @include themed() {
        .viewAll {
          margin-left: auto;
          margin-right: 15px;
          background: t('block');
          padding: 10px 15px;
          cursor: pointer;
          color: t('link');
          border-radius: 6px;
        }

        .arrows {
          display: flex;

          .arrow {
            padding: 13px;
            background: t('block');
            color: t('text');
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;

            @media(max-width: 991px) {
              padding: 5px 10px;
            }

            &.disabled {
              cursor: default;

              i, svg {
                opacity: .5;
                pointer-events: none;
              }
            }

            &:first-child {
              border-top-left-radius: 10px;
              border-bottom-left-radius: 10px;
            }

            &:last-child {
              border-top-right-radius: 10px;
              border-bottom-right-radius: 10px;
              margin-left: -1px;
            }

            i, svg {
              font-size: .9em;
              transition: all .3s ease;
            }

            &:not(.disabled):hover {
              i, svg {
                transform: scale(1.15);
              }
            }
          }
        }
      }
    }
  }
</style>
