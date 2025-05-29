<template>
  <div class="walletExchangeSelectors">
    <div class="walletExchangeSelector">
      <div class="wesContainer" @click="expand = !expand">
        <div class="icon" v-if="chevron"><web-icon icon="fal fa-fw fa-chevron-down"></web-icon></div>
        <div class="icon" v-if="selected.icon"><web-icon :icon="selected.icon" :style="{ color: selected.style }"></web-icon></div>
        <div class="name">{{ selected.name }}</div>
        <div class="dropdown-icon"><i class="fal fa-angle-down"></i></div>
      </div>
      <div class="exchangeList" v-if="expand">
        <overlay-scrollbars :options="{ scrollbars: { autoHide: 'leave' }, className: 'os-theme-thin-light' }">
          <div class="elEntry" v-for="currency in entries" @click="selected = currency; expand = false; onSelect(selected)">
            <div class="icon" v-if="currency.icon"><web-icon :icon="currency.icon" :style="{ color: currency.style }"></web-icon></div>
            <div class="name">{{ currency.name }}</div>
          </div>
        </overlay-scrollbars>
      </div>
    </div>
  </div>
</template>

<script>
  import WebIcon from "./WebIcon.vue";

  export default {
    data() {
      return {
        expand: false,
        selected: null
      }
    },
    created() {
      if(!this.select)
        this.selected = this.entries[0];
      else
        this.selected = this.entries.filter(e => e.id === this.select)[0];
    },
    props: ['entries', 'onSelect', 'select', 'chevron'],
    components: {
      WebIcon
    }
  }
</script>

<style lang="scss" scoped>
  @import "resources/sass/themes";

  .walletExchangeSelectors {
    display: flex;
    width: 100%;

    @include themed() {
      i {
        display: flex;
        align-items: center;
        justify-content: center;
      }

      .walletExchangeSelector {
        position: relative;
        cursor: pointer;
        margin-right: 15px;
        width: 100%;

        &:last-child {
          margin-right: 0;
        }

        .exchangeList {
          position: absolute;
          left: 0;
          width: 100%;
          z-index: 5;
          background: #292524;
          border-radius: .75rem;

          .os-host {
            max-height: 300px;
          }

          .elEntry {
            transition: background .3s ease;
            display: flex;
            align-items: center;
            padding: 10px 20px;
            white-space: nowrap;
            border-radius: .75rem;

            &:hover {
              background: rgb(28,25,23);
            }

            .icon {
              width: 25px;
              display: flex;
              align-items: center;
              justify-content: center;
              margin-right: 5px;
            }
          }
        }

        .wesContainer {
          display: flex;
          padding: 10px 20px;
          //background: #292524;
          transition: background .3s ease;
          white-space: nowrap;
          align-items: center;
          min-width: 145px;

          box-shadow: inset 0 0 #fff, inset 0 0 0 1px #44403c, 0 1px 2px #0000000d;
          border-radius: .75rem;
          width: 100%;

          &:hover {
            background: #292524d9;
          }

          .dropdown-icon  {
            margin-left: auto;
          }

          .icon {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 9px;
          }

          .name {
            margin-right: 10px;
          }
        }
      }
    }
  }
</style>
