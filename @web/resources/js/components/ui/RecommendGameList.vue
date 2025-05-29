<template>
  <div v-if="games" class="game-list" id="recommended-games">
    <template v-if="filteredGames.length > 0">
      <div class="category">
        <div class="icon">
          <img src="/img/misc/top-games.svg" alt="top-games">
        </div>
        <div class="name">
          {{$t('general.recommended_games')}}
        </div>
        <div class="viewAll" @click="$router.push('/recommended-games')">
          {{ $t('general.viewAll') }}
        </div>
        <div class="arrows">
          <div class="arrow" 
               @click="findPage('recommend').current > 0 ? updatePage({ ...findPage('recommend'), current: findPage('recommend').current - 1 }) : null"
               :class="{ disabled: findPage('recommend').current <= 0 }">
            <web-icon icon="fal fa-chevron-left"></web-icon>
          </div>
          <div class="arrow" 
               @click="findPage('recommend').current < findPage('recommend').max ? updatePage({ ...findPage('recommend'), current: findPage('recommend').current + 1 }) : null"
               :class="{ disabled: findPage('recommend').current >= findPage('recommend').max }">
            <web-icon icon="fal fa-chevron-right"></web-icon>
          </div>
        </div>
      </div>
      <div class="category-games" :key="'recommend'">
        <game-list-entry v-for="game in paginatedGames('recommend')" :key="game.id" :game="game"></game-list-entry>
      </div>
    </template>
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
    }
  },
  computed: {
    ...mapGetters(['games']),
    filteredGames() {
      const highPriorityGameIds = [
        "external:fc:vs20fruitsw", "external:fc:1hl65ce1lxuqdrkr", "external:fc:bigbassnewbb1320", 
        "external:fc:vs20sugarrush", "external:fc:spacemanyxe123nh", "external:fc:vs20olympx", 
        "external:fc:md500q83g7cdefw1", "external:fc:vswaysdogs",  "external:fc:LightningTable01", 
        "external:fc:h22z8qhp17sa0vkh", "external:fc:vs20goldfever", "blackjack"
      ];
      const gameIds = [
        "external:fc:vs20fruitsw", "external:fc:1hl65ce1lxuqdrkr", "external:fc:bigbassnewbb1320", 
        "external:fc:vs20sugarrush", "external:fc:spacemanyxe123nh", "external:fc:vs20olympx", 
        "external:fc:md500q83g7cdefw1", "external:fc:vswaysdogs", 
        "external:fc:LightningTable01", "external:fc:h22z8qhp17sa0vkh", "external:fc:vs20goldfever", 
        "external:fc:vs10fisheye", "external:fc:vswaystut", "external:fc:FunkyTime0000001", 
        "external:fc:vswayszombcarn", "external:fc:k2oswnib7jjaaznw", "external:fc:vs20gobnudge", 
        "external:fc:vswaysbbb", "external:fc:7nyiaws9tgqrzaz3", "external:fc:vswayslight", 
        "external:fc:GoldVaultRo00001", "external:fc:vs20candyblitz", "external:fc:SweetSugar", 
        "external:fc:vs20starlightx", "external:fc:5bzl2835s5ruvweg", "external:fc:Monopoly00000001", 
        "external:fc:CrazyTime0000001", "external:fc:vs20fruitparty", "external:fc:15_golden_eggs", 
        "external:fc:r20speedrtwo201s", "external:fc:vs10starpirate", "external:fc:1hl323e1lxuqdrkr", 
        "external:fc:vs25goldparty", "external:fc:jzbzy021lg8xy9i2", "external:fc:pbvzrfk1fyft4dwe", 
        "external:fc:oytmvb9m1zysmc44", "external:fc:vs243queenie", 
        "external:fc:vs40demonpots", "external:fc:TopCard000000001", "external:fc:vs10dyndigd", 
        "external:fc:228001", "external:fc:vs20bnnzdice", "external:fc:vs20procount", "external:fc:vs40wildwest",
        "external:fc:XxxtremeLigh0001", "external:fc:StockMarket00001", "blackjack", "plinko"
      ];
      
      const filteredGames = this.games.filter(game => gameIds.includes(game.id));
      const uniqueGames = Array.from(new Set(filteredGames.map(game => game.id))).map(id => filteredGames.find(game => game.id === id));
      
      const highPriorityGames = uniqueGames.filter(game => highPriorityGameIds.includes(game.id));
      const remainingGames = uniqueGames.filter(game => !highPriorityGameIds.includes(game.id));
      
      this.shuffleArray(highPriorityGames);
      this.shuffleArray(remainingGames);
      
      return [...highPriorityGames, ...remainingGames];
    }
  },
  watch: {
    games() {
      this.updateGames();
    }
  },
  mounted() {
    this.updateGamesPerView();
    window.addEventListener('resize', this.updateGamesPerView);
    this.updatePage({
      id: 'recommend',
      current: 0,
      max: Math.floor((this.filteredGames.length - 1) / this.gamesPerView)
    });
  },
  methods: {
    updateGamesPerView() {
      this.gamesPerView = window.innerWidth <= 991 ? 9 : 12;
      this.updateGames();
    },
    findPage(type) {
      return this.page.find(e => e.id === type) || { current: 0, max: 0 };
    },
    updatePage(object) {
      const pageIndex = this.page.findIndex(e => e.id === object.id);
      if (pageIndex !== -1) {
        this.page.splice(pageIndex, 1, object);
      } else {
        this.page.push(object);
      }
    },
    updateGames() {
      this.updatePage({
        id: 'recommend',
        current: 0,
        max: Math.floor((this.filteredGames.length - 1) / this.gamesPerView)
      });
    },
    paginatedGames(pageId) {
      const page = this.findPage(pageId);
      return this.filteredGames.slice(page.current * this.gamesPerView, (page.current + 1) * this.gamesPerView);
    },
    shuffleArray(array) {
      for (let i = array.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [array[i], array[j]] = [array[j], array[i]];
      }
    }
  }
}
</script>
