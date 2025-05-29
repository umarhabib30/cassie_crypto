<template>
  <div v-if="slotGames.length > 0" class="container pageContent" id="slot-games">
    <div class="game-list">
      <div class="category">
        <div class="icon">
          <img src="/img/misc/slot.svg" alt="Slot Games">
        </div>
        <div class="name">
          {{$t('general.all_slots')}}
        </div>
        <div class="viewAll" style="opacity: 0"></div>
      </div>
      <div class="category-games" :key="'slot'" :class="(!sort ? '' : 'sorted')">
        <game-list-entry v-for="game in visibleGames" :key="game.id" :game="game"></game-list-entry>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import GameListEntry from "../ui/GameListEntry.vue";

export default {
  components: { GameListEntry },
  data() {
    return {
      width: 0,
      visibleGames: [],
      gamesToLoad: 18,
      isLoadingMore: false
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
  computed: {
    ...mapGetters(['games']),
    slotGames() {
      return this.shuffleArray(this.games.filter(game => game.category.includes('slots')));
    }
  },
  mounted() {
    this.updateGamesPerView();
    window.addEventListener('resize', this.updateGamesPerView);
    window.addEventListener('scroll', this.onScroll);
    this.loadInitialGames();
  },
  beforeDestroy() {
    window.removeEventListener('resize', this.updateGamesPerView);
    window.removeEventListener('scroll', this.onScroll);
  },
  methods: {
    updateGamesPerView() {
      this.width = window.innerWidth;
    },
    shuffleArray(array) {
      for (let i = array.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [array[i], array[j]] = [array[j], array[i]];
      }
      return array;
    },
    loadInitialGames() {
      this.visibleGames = this.slotGames.slice(0, this.gamesToLoad);
    },
    loadMoreGames() {
      if (this.isLoadingMore) return;
      this.isLoadingMore = true;
      setTimeout(() => {
        const start = this.visibleGames.length;
        const end = start + this.gamesToLoad;
        this.visibleGames = this.visibleGames.concat(this.slotGames.slice(start, end));
        this.isLoadingMore = false;
      }, 500);
    },
    onScroll() {
      const bottomOfWindow = window.innerHeight + window.scrollY >= document.documentElement.offsetHeight - 200;
      if (bottomOfWindow && this.visibleGames.length < this.slotGames.length) {
        this.loadMoreGames();
      }
    }
  }
}
</script>

<style scoped>
.load-more-container {
  text-align: center;
  margin: 50px 0 0 0;
}
</style>
