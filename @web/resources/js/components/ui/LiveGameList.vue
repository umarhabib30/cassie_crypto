<template>
  <div v-if="liveGames.length > 0" class="game-list" id="live-games">
    <div class="category">
      <div class="icon">
        <img src="/img/misc/live.svg" alt="">
      </div>
      <div class="name">
        {{$t('general.all_live')}}
      </div>
      <div class="viewAll" @click="$router.push('/live-games')">
        {{ $t('general.viewAll') }}
      </div>
      <div class="arrows">
        <div class="arrow" @click="findPage('live').current > 0 ? updatePage(Object.assign(findPage('live'), { current: findPage('live').current - 1 })) : null"
             :class="(findPage('live') && findPage('live').current <= 0) ? 'disabled' : ''"><web-icon icon="fal fa-chevron-left"></web-icon></div>
        <div class="arrow" @click="findPage('live').current < findPage('live').max ? updatePage(Object.assign(findPage('live'), { current: findPage('live').current + 1 })) : null"
             :class="(findPage('live') && findPage('live').current >= findPage('live').max) ? 'disabled' : ''"><web-icon icon="fal fa-chevron-right"></web-icon></div>
      </div>
    </div>
    <div class="category-games" :key="'live'" :class="(!sort ? '' : 'sorted')">
      <game-list-entry v-for="game in liveGames.slice(findPage('live')?findPage('live').current * gamesPerView:0, ((findPage('live')?findPage('live').current:0) + 1) * gamesPerView)" :key="game.id" :game="game"></game-list-entry>
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
    liveGames() {
      return this.shuffleArray(this.games.filter(game => game.category.includes('live')));
    }
  },
  mounted() {
    this.updateGamesPerView();
    window.addEventListener('resize', this.updateGamesPerView);
    this.updatePage({
      id: 'live',
      current: 0,
      max: Math.floor((this.liveGames.length - 1) / this.gamesPerView)
    });
  },
  methods: {
    updateGamesPerView() {
      this.width = window.innerWidth;

      let prev = this.gamesPerView;
      if (window.innerWidth <= 991) this.gamesPerView = 9;
      else this.gamesPerView = 12;

      if (prev !== this.gamesPerView) this.updateGames();
    },
    findPage(type) {
      return this.page.filter((e) => e.id === type)[0];
    },
    updatePage(object) {
      this.page = this.page.filter((e) => e.id !== object.id);
      this.page.push(object);
    },
    updateGames() {
      this.updatePage({
        id: 'live',
        current: 0,
        max: Math.floor((this.liveGames.length - 1) / this.gamesPerView)
      });
    },
    shuffleArray(array) {
      for (let i = array.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [array[i], array[j]] = [array[j], array[i]];
      }
      return array;
    }
  }
}
</script>
