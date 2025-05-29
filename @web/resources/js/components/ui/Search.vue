<template>
  <div class="search" :class="show ? 'show' : ''">
    <div class="search-bg" @click="show = false"></div>
    <div class="search-container">
      <div class="input">
        <input id="searchInput" v-model="search" placeholder="Zoeken...">
        <web-icon icon="fal fa-search" class="search"></web-icon>
        <div @click="show = false" class="close"><web-icon icon="fal fa-times" class="close"></web-icon></div>
      </div>
      <div class="results">
        <div class="results-full" v-if="search === ''">
          <div class="links">
            <div class="name">{{$t('general.recommended_games')}}</div>
            <div class="link-group">
              <router-link 
                v-for="game in recommendedGames" 
                :key="game.id" 
                :to="`/casino/game/` + game.id" 
                class="game" 
                @click.native="handleGameClick">
                <img :src="game.image" :alt="game.name" class="game-img" @error="handleImageError($event, game)">
                <div>
                  <div>{{ game.name }}</div>
                  <div>{{ game.type }}</div>
                </div>
              </router-link>
            </div>
          </div>
        </div>
        <div class="results-full" v-else>
          <div 
            class="result" 
            @click="handleGameClick(result._searchUrl)" 
            v-for="result in results" 
            :key="result.id">
            <img :src="result.image || '/img/misc/default-image.jpg'" :alt="result.name" @error="handleImageError($event, result)">
            <div>
              <div class="game-name">{{ result.name }}</div>
              <div class="game-provider">{{ result.type }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import Bus from "../../bus";
import AuthModal from "../modals/AuthModal.vue";

export default {
  computed: {
    ...mapGetters(['games', 'isGuest']),
    recommendedGames() {
      const gameIds = [
        "external:fc:vs20fruitsw", "external:fc:1hl65ce1lxuqdrkr", "external:fc:bigbassnewbb1320", 
        "external:fc:vs20sugarrush", "external:fc:spacemanyxe123nh", "external:fc:vs20olympx", 
        "external:fc:md500q83g7cdefw1", "external:fc:vs20gembondx", "external:fc:vswaysdogs", 
        "external:fc:LightningTable01", "external:fc:h22z8qhp17sa0vkh", "external:fc:vs20goldfever", 
        "external:fc:vs10fisheye", "external:fc:vswaystut", "external:fc:FunkyTime0000001", 
        "external:fc:vswayszombcarn", "external:fc:k2oswnib7jjaaznw", "external:fc:vs20gobnudge", 
        "external:fc:vswaysbbb", "external:fc:7nyiaws9tgqrzaz3", "external:fc:vswayslight", 
        "external:fc:GoldVaultRo00001", "external:fc:vs20candyblitz", "external:fc:SweetSugar", 
        "external:fc:vs20starlightx", "external:fc:5bzl2835s5ruvweg", "external:fc:Monopoly00000001", 
        "external:fc:CrazyTime0000001", "external:fc:vs20fruitparty", "external:fc:15_golden_eggs", 
        "external:fc:r20speedrtwo201s", "external:fc:vs10starpirate", "external:fc:1hl323e1lxuqdrkr", 
        "external:fc:vs25goldparty", "external:fc:jzbzy021lg8xy9i2", "external:fc:pbvzrfk1fyft4dwe", 
        "external:fc:vswaysrsm", "external:fc:oytmvb9m1zysmc44", "external:fc:vs243queenie", 
        "external:fc:vs40demonpots", "external:fc:TopCard000000001", "external:fc:vs10dyndigd", 
        "external:fc:228001", "external:fc:vs20bnnzdice", "external:fc:vs20procount", "external:fc:vs40wildwest",
        "external:fc:XxxtremeLigh0001", "external:fc:StockMarket00001"
      ];
      const filteredGames = this.games.filter(game => gameIds.includes(game.id));
      const uniqueGames = Array.from(new Set(filteredGames.map(game => game.id)))
                                .map(id => filteredGames.find(game => game.id === id));
      return uniqueGames.sort((a, b) => gameIds.indexOf(a.id) - gameIds.indexOf(b.id));
    }
  },
  data() {
    return {
      show: false,
      search: '',
      results: []
    };
  },
  watch: {
    show() {
      if (this.show) {
        setTimeout(() => {
          document.querySelector('#searchInput').focus();
        });
      }
    },
    search() {
      if (this.search === '' || this.search.length < 3) {
        this.results = [];
        return;
      }
      let results = [];
      this.games.forEach((game) => {
        if (game.name.toLowerCase().includes(this.search.toLowerCase()) ||
            game.id.toLowerCase().includes(this.search.toLowerCase()) ||
            game.category[0].toLowerCase().includes(this.search.toLowerCase())) {
          game._searchUrl = '/casino/game/' + game.id;
          results.push(game);
        }
      });
      this.results = results;
    }
  },
  methods: {
    openAuthModal(type) {
      AuthModal.methods.open(type);
    },
    handleGameClick(url) {
      if (this.isGuest && !this.isHovered) {
        this.show = false;
        this.openAuthModal('auth');
      } else {
        this.show = false;
        this.$router.push(url);
      }
    },
    handleImageError(event, game) {
      const gameType = game.type.replace(/\s+/g, '-').toUpperCase();
      const filename = game.image.split('/').pop();
      const localImageSrc = `/img/banners/${gameType}/${filename}`;

      event.target.onerror = () => {
        event.target.src = '/img/misc/image-not-found.png';
        event.target.alt = game.name;
      };

      event.target.src = localImageSrc;
    }
  },
  created() {
    Bus.$on('search:toggle', () => this.show = !this.show);
  }
};
</script>


<style lang="scss">
    @import "resources/sass/themes";

    .search {
        position: fixed;
        top: 0;
        left: 0;
        z-index: 99999;
        pointer-events: none;
        width: 100%;
        height: 100%;
        opacity: 0;
        transition: opacity .3s ease;

        .search-bg {
            position: fixed;
            left: 0px;
            top: 0px;
            width: 100%;
            height: 100%;
            background: rgba(41, 37, 36, .75);
        }

        .search-container {
            width: 415px;
            max-width: 95%;
            background: rgba(28, 25, 23);
            margin: auto;
            border-radius: .5rem;
            overflow: hidden;
            position: relative;
            top: 15%;
        }

        &.show {
            opacity: 1;
            pointer-events: unset;
        }

        .results {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 20px;
            height: 70vh;
            overflow: auto;
            .results-full {
              width: 100%;
            }
            .os-content {
                display: flex;
                flex-wrap: wrap;
            }

            .name {
                padding: 15px 25px;
            }

        }

        .result,
        .link-group a {
            cursor: pointer;
            transition: all .3s ease;
            margin-bottom: 15px;
            width: 100%;
            /* height: 145px; */
            /* border-radius: 10px; */
            display: flex;
            gap: 10px;
            padding: 0px 20px;
            color: #b7b7b7;
            &:hover {
              color: #fff;
            }

            img {
                width: 60px;
                height: 53px;
                border-radius: 10px;
            }
        }

        .input {
            position: relative;
            margin-bottom: -20px;

            .icon {
                position: absolute;
                left: 30px;
                top: 50%;
                transform: translateY(-50%);
                opacity: .5;
            }

            .close {
                position: absolute;
                right: 15px;
                top: 50%;
                transform: translateY(-50%);
                opacity: .5;
                cursor: pointer;
                transition: opacity .3s ease;
                text-shadow: unset;
                color: white;

                &:hover {
                    opacity: 1;
                }
            }

            input {
                border: none;
                border-radius: 0px;
                color: #fff;
                outline: none;
                transition: box-shadow .1s ease, background .1s ease, color .1s ease;
                width: 100%;
                box-shadow: none;
            }
        }
    }
</style>
