<template>
    <div v-if="originalClassicGames.length > 0" class="container pageContent" id="original-games">
      <div class="game-list">
        <div class="category">
          <div class="icon">
            <img src="/img/misc/original.svg" alt="Original Games">
          </div>
          <div class="name">
            {{ $t('general.all_originals') }}
          </div>
          <div class="viewAll" style="opacity: 0"></div>
        </div>
        <div class="category-games" :key="'original'" :class="(!sort ? '' : 'sorted')">
          <game-list-entry v-for="game in visibleGames" :key="game.id" :game="game"></game-list-entry>
        </div>
        <div v-if="visibleGames.length < originalClassicGames.length" class="load-more-container">
          <button @click="loadMoreGames" :disabled="isLoadingMore" class="btn">{{ loadMoreButtonText }}</button>
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
      originalClassicGames() {
        return this.shuffleArray(
            this.games.filter(game => game.type.includes('Originals') && !game.isHidden)

        );
      },
      loadMoreButtonText() {
        return this.isLoadingMore ? 'Loading...' : `Load More (${this.visibleGames.length}/${this.originalClassicGames.length})`;
      }
    },
    mounted() {
      this.updateGamesPerView();
      window.addEventListener('resize', this.updateGamesPerView);
      this.loadInitialGames();
    },
    beforeDestroy() {
      window.removeEventListener('resize', this.updateGamesPerView);
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
        this.visibleGames = this.originalClassicGames.slice(0, this.gamesToLoad);
      },
      loadMoreGames() {
        if (this.isLoadingMore) return;
        this.isLoadingMore = true;
        setTimeout(() => {
          const start = this.visibleGames.length;
          const end = start + this.gamesToLoad;
          this.visibleGames = this.visibleGames.concat(this.originalClassicGames.slice(start, end));
          this.isLoadingMore = false;
        }, 500);
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
  