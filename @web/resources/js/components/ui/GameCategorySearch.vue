<template>
  <div class="c-search">
    <div class="search-input" @click="openSearch">
      <web-icon icon="fal fa-search"></web-icon>
      <input :placeholder="$t('general.search')">
    </div>
  </div>
</template>

<script>
  import Bus from "../../bus";
  import WebIcon from "../ui/WebIcon.vue";

  export default {
    methods: {
      openSearch() {
        Bus.$emit('search:toggle');
      }
    },
    components: {
      WebIcon
    }
  }
</script>

<style lang="scss" scoped>
  @import "resources/sass/themes";

  .c-search {
    @include themed() {
      display: none;
      align-items: center;

      @media (max-width: 991px) {
        display: flex;
        background: transparent;
        flex-direction: column;
        align-items: unset;
        padding: unset;
        margin-top: 0;

        .search-input {
          margin-left: unset;
        }
      }
    }

    .search-input {
      position: relative;
      cursor: pointer;
      width: 100%;

      input {
        padding-left: 50px;
        pointer-events: none;

        background: rgb(28, 25, 23);
        border-radius: .75rem;
        color: #78716c;
        box-shadow: inset 0 0 #fff, inset 0 0 0 1px #44403c, 0 1px 2px #0000000d;

        @include themed() {
          &::placeholder {
            color: t('link');
          }
        }
      }

      svg, i {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        left: 20px;
      }
    }
  }
</style>
