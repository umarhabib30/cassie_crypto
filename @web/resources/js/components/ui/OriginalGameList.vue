<template>
  <div v-if="originalGames.length > 0" class="game-list" id="original-games">
    <div class="category">
      <div class="icon">
        <img src="/img/misc/original.svg" alt="">
      </div>
      <div class="name">
        {{ $t('general.all_originals') }}
      </div>
      <div class="viewAll" @click="$router.push('/original-games')">
        {{ $t('general.viewAll') }}
      </div>
      <div class="arrows">
        <div class="arrow" @click="decrementPage('original')"
             :class="{ disabled: findPage('original').current <= 0 }">
          <web-icon icon="fal fa-chevron-left"></web-icon>
        </div>
        <div class="arrow" @click="incrementPage('original')"
             :class="{ disabled: findPage('original').current >= findPage('original').max }">
          <web-icon icon="fal fa-chevron-right"></web-icon>
        </div>
      </div>
    </div>
    <div class="category-games" :key="'original'" :class="sort ? 'sorted' : ''">
      <game-list-entry v-for="game in paginatedGames('original')" :key="game.id" :game="game"></game-list-entry>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import GameListEntry from "./GameListEntry.vue";

export default {
  components: { GameListEntry },
  data() {
    return {
      gamesPerView: 0,
      page: [],
      width: 0
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
    }
  },
  computed: {
    ...mapGetters(['games']),
    originalGames() {
      return this.games.filter(game => game.type.includes('Originals') && !game.isHidden);
    }
  },
  mounted() {
    this.updateGamesPerView();
    window.addEventListener('resize', this.updateGamesPerView);
    this.initializePagination('original');
  },
  methods: {
    updateGamesPerView() {
      this.width = window.innerWidth;
      const prevGamesPerView = this.gamesPerView;

      if (window.innerWidth <= 991) this.gamesPerView = 9;
      else this.gamesPerView = 12;

      if (prevGamesPerView !== this.gamesPerView) this.updateGames();
    },
    initializePagination(type) {
      this.updatePage({
        id: type,
        current: 0,
        max: Math.floor((this.originalGames.length - 1) / this.gamesPerView)
      });
    },
    findPage(type) {
      return this.page.find(e => e.id === type) || { current: 0, max: 0 };
    },
    updatePage(object) {
      const existingPageIndex = this.page.findIndex(e => e.id === object.id);
      if (existingPageIndex !== -1) {
        this.$set(this.page, existingPageIndex, object);
      } else {
        this.page.push(object);
      }
    },
    updateGames() {
      this.initializePagination('original');
    },
    paginatedGames(type) {
      const page = this.findPage(type);
      const startIndex = page.current * this.gamesPerView;
      const endIndex = startIndex + this.gamesPerView;
      return this.originalGames.slice(startIndex, endIndex);
    },
    incrementPage(type) {
      const page = this.findPage(type);
      if (page.current < page.max) {
        this.updatePage({ ...page, current: page.current + 1 });
      }
    },
    decrementPage(type) {
      const page = this.findPage(type);
      if (page.current > 0) {
        this.updatePage({ ...page, current: page.current - 1 });
      }
    }
  }
}
</script>
