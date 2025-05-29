<template>
  <div v-if="games" class="game-list">
    <template v-for="provider in sort && sort.type === 'provider' ? providers.filter(e => e === sort.by) : ($isAvailable('externalSlots') ? providers.filter(e => e.includes('Originals')) : providers)">
      <template v-if="findPage(provider) && sortGames(provider).length > 0">
        <div :key="provider" class="category">
          <div class="icon">
            <!--<web-icon :icon="icons[provider] ? icons[provider] : icons['default']"></web-icon>-->
            <img src="/img/misc/providers.svg" alt="Providers">
          </div>
          <div class="name">
            {{ provider }}
          </div>
          <div class="viewAll" @click="$router.push('/casino/game/provider/' + provider)" v-if="!sort">
            {{ $t('general.viewAll') }}
          </div>
          <div class="arrows" v-if="!sort">
            <div class="arrow" @click="findPage(provider).current > 0 ? updatePage(Object.assign(findPage(provider), { current: findPage(provider).current - 1 })) : null"
                 :class="findPage(provider).current <= 0 ? 'disabled' : ''"><web-icon icon="fal fa-chevron-left"></web-icon></div>
            <div class="arrow" @click="findPage(provider).current < findPage(provider).max ? updatePage(Object.assign(findPage(provider), { current: findPage(provider).current + 1 })) : null"
                 :class="findPage(provider).current >= findPage(provider).max ? 'disabled' : ''"><web-icon icon="fal fa-chevron-right"></web-icon></div>
          </div>
        </div>
        <div class="category-games" :key="provider + '1'" :class="(provider.replaceAll(' ', '_').replaceAll('(', '').replaceAll(')', '') + ' ') + (!sort ? '' : 'sorted')">
          <template v-for="(game) in isIndex ? sortGames(provider).slice(0, gamesPerView) : sortGames(provider)">
            <game-list-entry :game="game"></game-list-entry>
          </template>
        </div>
      </template>
    </template>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex';
  import GameListEntry from "./GameListEntry.vue";

  export default {
    components: {GameListEntry},
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
      }/*,
      filteredGames() {
        const gameIds = ["external:fc:vs20fruitsw", "external:fc:AmericanTable001", "external:fc:k2oswnib7jjaaznw", "external:fc:vswaysconcoll", "external:fc:SweetSugar"];
        return this.games.filter(game => gameIds.includes(game.id));
      }*/
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
        if(window.innerWidth <= 991) this.gamesPerView = 9;
        else this.gamesPerView = 12;

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
