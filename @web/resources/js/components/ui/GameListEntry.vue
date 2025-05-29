<template>
  <div class="category-game swiper-slide" @click="handleClick">
    <div class="unavailable" v-if="game.isDisabled">
      <div class="slanting">
        <div class="content">
          {{ $t(game.isPlaceholder ? 'general.coming_soon' : 'general.not_available') }}
        </div>
      </div>
    </div>
    <div class="image">
      <img :src="game.image" :alt="game.name" class="game-img" @error="handleImageError">
    </div>
    <div class="gameInfo">
      <div>{{ game.name }}</div>
      <div>{{ game.type }}</div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import AuthModal from "../modals/AuthModal.vue";

export default {
  props: [
    'game'
  ],
  methods: {
    openAuthModal(type) {
      AuthModal.methods.open(type);
    },
    handleClick() {
      if (this.isGuest && !this.isHovered) {
        this.openAuthModal('auth');
      } else {
        this.$router.push('/casino/game/' + this.game.id);
      }
    },
    handleImageError(event) {
      const gameType = this.game.type.replace(/\s+/g, '-').toUpperCase();
      const filename = this.game.image.split('/').pop();
      const localImageSrc = `/img/banners/${gameType}/${filename}`;

      event.target.onerror = () => {
        event.target.src = '/img/misc/image-not-found.png';
        event.target.alt = 'Image not found';
      };
      
      event.target.src = localImageSrc;
    }
  },
  computed: {
    ...mapGetters(['isGuest', 'user'])
  }
}
</script>
