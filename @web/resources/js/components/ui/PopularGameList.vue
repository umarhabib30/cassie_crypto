<template>
  <div v-if="games" class="game-list">
    <template>
      <div class="category">
        <div class="icon">
          <web-icon icon="stars"></web-icon>
        </div>
        <div class="name">
          {{ $t('general.popular') }}
        </div>
        <div class="viewAll" style="opacity: 0"></div>
        <div class="arrows">
          <div class="arrow" @click="findPage('popular').current > 0 ? updatePage(Object.assign(findPage('popular'), { current: findPage('popular').current - 1 })) : null"
               :class="findPage('popular').current <= 0 ? 'disabled' : ''"><web-icon icon="fal fa-chevron-left"></web-icon></div>
          <div class="arrow" @click="findPage('popular').current < findPage('popular').max ? updatePage(Object.assign(findPage('popular'), { current: findPage('popular').current + 1 })) : null"
               :class="findPage('popular').current >= findPage('popular').max ? 'disabled' : ''"><web-icon icon="fal fa-chevron-right"></web-icon></div>
        </div>
      </div>
      <div class="category-games" :key="'popular'" :class="(!sort ? '' : 'sorted')">
        <game-list-entry v-for="(game) in popularGames.slice(findPage('popular').current * gamesPerView, (findPage('popular').current + 1) * gamesPerView)" :key="game.id" :game="game"></game-list-entry>
      </div>
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