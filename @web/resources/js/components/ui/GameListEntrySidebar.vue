<template>
  <div class="category-game" @click="handleClick">
    <div class="unavailable" v-if="game.isDisabled">
      <div class="slanting">
        <div class="content">
          {{ $t(game.isPlaceholder ? 'general.coming_soon' : 'general.not_available') }}
        </div>
      </div>
    </div>
    <div class="image" :style="{ backgroundImage: `url(${game.image})` }"></div>
    <!--
    <div class="gameInfo">
      <div>{{ game.name }}</div>
      <div>{{ game.type }}</div>
    </div>-->
  </div>
</template>

<script>
  import {mapGetters} from 'vuex';
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
        // Open the login modal only if the user is a guest and the button is not being hovered
        this.openAuthModal('auth');
      } else {
        // Open the game for all other cases
        this.$router.push('/casino/game/' + this.game.id);
      }
    },
    },
    computed: {
      ...mapGetters(['isGuest', 'user'])
    }
  }

</script>
