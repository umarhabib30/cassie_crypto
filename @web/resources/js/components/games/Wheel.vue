<template>
  <div class="wheel-container"></div>
</template>

<script>
  import Bus from '../../bus.js';
  import {mapGetters} from 'vuex';

  const green = "#3bc248", red = "#e76376", black = "#1c2028", yellow = "#fec545";

  export default {
    data() {
      return {

        mode: 'double',
        target: 'red',
        latestGame: null
      }
    },
    computed: {
      ...mapGetters(['quick', 'gameInstance'])
    },
    watch: {
      mode() {
        $(`.wheel-${this.mode === 'double' ? 'x50' : 'double'}`).hide();
        $(`.wheel-${this.mode}`).show();
        this.init();
      },
      quick() {
        this.init();
      },
      // gameInstance() {
      //   $('.wheel-double .wheel-button-red span').text('x' + this.gameInstance.game.data['wheel-double-red']);
      //   $('.wheel-double .wheel-button-red span').text('x' + this.gameInstance.game.data['wheel-double-black']);
      //   $('.wheel-double .wheel-button-red span').text('x' + this.gameInstance.game.data['wheel-double-green']);
      //   $('.wheel-x50 .wheel-button-red span').text('x' + this.gameInstance.game.data['wheel-x50-red']);
      //   $('.wheel-x50 .wheel-button-black span').text('x' + this.gameInstance.game.data['wheel-x50-black']); 
      //   $('.wheel-x50 .wheel-button-green span').text('x' + this.gameInstance.game.data['wheel-x50-green']); 
      //   $('.wheel-x50 .wheel-button-yellow span').text('x' + this.gameInstance.game.data['wheel-x50-yellow']);
      // }

    },
    created() {
      window.$bus.$on('wheel:initCompleted', this.init);
    },
    mounted() {
      if($.fn.wheel) this.init();
    },
    methods: {
      init() {
        $('.wheel-container').html('<div class="wheel"></div>');

        const fill = function (slices) {
          let output = [], i = 0;
          _.forEach(slices, function (slice) {
            output.push({
              value: i,
              background: slice
            });
            i++;
          });
          return output;
        };

        if (this.mode === 'double') {
          $('.wheel-container').find('.wheel').wheel({
            slices: fill([green, red, black, red, black, red, black, red, black, red, black, red, black, red, black]),
            width: 360,
            frame: 1,
            type: "spin",
            duration: this.quick ? 1500 : 8000,
            line: {
              width: 0,
              color: "transparent"
            },
            outer: {
              width: 8,
              color: "rgba(255, 255, 255, 0.1)"
            },
            inner: {
              width: 0,
              color: "transparent"
            },
            center: {
              width: 90,
              rotate: true
            },
            marker: {
              animate: "true"
            }
          });
        } else if (this.mode === 'x50') {
          $('.wheel-container').find('.wheel').wheel({
            slices: fill([
              yellow, green, black, red, black, red, black, red, black, green, black, green, black, red, black, red, black,
              red, black, green, black, green, black, red, black, red, black, red, black, red, black, red, black, green, black,
              green, black, red, black, red, black, red, black, green, black, green, black, red, black, red, black, red, black, red, black, green
            ]),
            width: 360,
            frame: 1,
            type: "spin",
            duration: this.quick ? 1500 : 8000,
            line: {
              width: 0,
              color: "transparent"
            },
            outer: {
              width: 8,
              color: "rgba(255, 255, 255, 0.1)"
            },
            inner: {
              width: 0,
              color: "transparent"
            },
            center: {
              width: 90,
              rotate: true
            },
            marker: {
              animate: "true"
            }
          });
        }

        $('.wheel-container').find('.wheel').wheel('onStep', () => this.playSound('/sounds/tick.mp3'));

        $('.wheel-container').find('.wheel').wheel('onComplete', () => {
          this.updateGameInstance((i) => i.playTimeout = false);

          if (!$('.wheel-container').parent().hasClass('overview-render-target')) {
            this.resultPopup(this.latestGame.game);
            Bus.$emit('wheel:history:addEntry', {html: `<div class="wheel-history-entry wheel-history-${this.latestGame.game.data.color}"></div>`});
          }
        });
      },

     // gameDataRetrieved(data) {
        // this.updateMultipliers();
        // $('.wheel-double .wheel-button-red span').text('x' + data['wheel-double-red']);
        // $('.wheel-double .wheel-button-red span').text('x' + data['wheel-double-black']);
        // $('.wheel-double .wheel-button-red span').text('x' + data['wheel-double-green']);
        // $('.wheel-x50 .wheel-button-red span').text('x' + data['wheel-x50-red']);
        // $('.wheel-x50 .wheel-button-black span').text('x' + data['wheel-x50-black']); 
        // $('.wheel-x50 .wheel-button-green span').text('x' + data['wheel-x50-green']); 
        // $('.wheel-x50 .wheel-button-yellow span').text('x' + data['wheel-x50-yellow']);
   //   },

      // updateMultipliers() {
      //   document.querySelectorAll('.history-mines').forEach(e => e.parentNode.removeChild(e));
      //   let multipliers = this.gameInstance.game.data[this.mines];
      //   window._.forEach(multipliers, (key, value) => {
      //     Bus.$emit('mines:history:addEntry', {
      //       html: `<div>${value}</div><div>x${this.abbreviate(key)}</div>`,
      //       type: 'append'
      //     });
      //   });
      // }value_name: value,

      callback(response) {
        $('.game-wheel .wheel').wheel('start', 'value', response.server_seed.result[this.mode === 'double' ? 0 : 1]);
        this.latestGame = response;
      },
      getClientData() {
        return {
          mode: this.mode,
          target: this.target
        };
      },
      async getSidebarComponents() {
        let data = await this.whisper('GameData', {api_id: "wheel"}).then((response) => {
      //    console.log('Fetched wheel game data and ready to initialize the sidebar' + response);
       //   console.log(response['wheel-double-red'].toFixed(4));
          
          return [
            {name: 'label', data: {label: this.$i18n.t('general.wager')}},
            {name: 'wager-classic'},
            {name: 'label', data: {label: this.$i18n.t('general.color')}},
            {
              name: 'buttons', data: {
                buttons: [
                  {
                     // label: 'x2.00',
                    label: 'x' + response['wheel-double-red'].toFixed(4),
                    class: 'wheel-double wheel-button wheel-button-red',
                    callback: () => this.target = 'red'
                  },
                  {
                     // label: 'x15.00',
                    label: 'x' + response['wheel-double-green'].toFixed(4),
                    class: 'wheel-double wheel-button wheel-button-green',
                    callback: () => this.target = 'green'
                  },
                  {
                    //  label: 'x2.00',
                    label: 'x' +  response['wheel-double-black'].toFixed(4),
                    class: 'wheel-double wheel-button wheel-button-black',
                    callback: () => this.target = 'black'
                  },
                ]
              }
            },
            {
              name: 'buttons', data: {
                buttons: [
                  {
                  //  label: 'x2.00',
                    label: 'x' +  response['wheel-x50-black'].toFixed(4),
                    class: 'wheel-x50 wheel-button wheel-button-black',
                    callback: () => this.target = 'black'
                  },
                  {
                   // label: 'x3.00', 
                  label: 'x' +  response['wheel-x50-red'].toFixed(4),
                    class: 'wheel-x50 wheel-button wheel-button-red',
                    callback: () => this.target = 'red'},
                  {
                    //label: 'x5.00',
                  label: 'x' +  response['wheel-x50-green'].toFixed(4),
                    class: 'wheel-x50 wheel-button wheel-button-green',
                    callback: () => this.target = 'green'
                  },
                  {
                  //  label: 'x50.00',
                    label: 'x' +  response['wheel-x50-yellow'].toFixed(4),
                    class: 'wheel-x50 wheel-button wheel-button-yellow',
                    callback: () => this.target = 'yellow'
                  },
                ]
              }
            },
            {name: 'label', data: {label: this.$i18n.t('general.game_mode')}},
            {
              name: 'buttons', data: {
                buttons: [
                  {
                    label: 'Double', class: 'wheel-button', callback: () => {
                      this.mode = 'double';
                      this.target = 'red';
                    }
                  },
                  {
                    label: 'X50', class: 'wheel-button', callback: () => {
                      this.mode = 'x50';
                      this.target = 'black';
                    }
                  }
                ]
              }
            },
            {name: 'auto-bets'},
            {name: 'play'},
            {name: 'footer', data: {buttons: ['help', 'quick', 'sound', 'stats']}},
            {name: 'history', data: {scrollable: true}}
          ];
        });

      //  console.log('Data returned from axios api call' + data);
      //  console.log(data);
        return data;
      }
    }
  }
</script>

<style lang="scss">
  @import '../../../sass/variables';

  .game-sidebar .game-sidebar-buttons-container .game-sidebar-buttons-container-button.wheel-x50 {
    @include themed() {
      display: none;
    }
  }

  .theme--dark {
    .game-wheel {
      .wheel-button-black {
        color: $gray-600 !important;
      }

      .wheel-history-black {
        background: $gray-600 !important;
      }
    }
  }

  .game-wheel {
    .history-wheel {
      border-radius: 3px;
      width: 60px;
      height: 3px;
    }

    .wheel-button {
      text-shadow: 1px 1px 1px rgba(black, 0.4);
    }

    .wheel-button-green {
      color: #3bc248 !important;
    }

    .wheel-button-red {
      color: #e76376 !important;
    }

    .wheel-button-black {
      color: #000000 !important;
    }

    .wheel-button-yellow {
      color: #fec545 !important;
    }

    .wheel-history-entry {
      width: 100%;
      height: 100%;
    }

    .wheel-history-green {
      background: #3bc248;
    }

    .wheel-history-red {
      background: #e76376;
    }

    .wheel-history-black {
      background: #1c2028;
    }

    .wheel-history-yellow {
      background: #fec545;
    }

    .game-content-wheel {
      .game-history {
        padding: 0 15px !important;
        height: 30px;
      }

      .wheel-container {
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
      }

      .wheel {
        margin: unset !important;
      }
    }

    @include media-breakpoint-down(md) {
      .sWheel-wrapper {
        width: 100% !important;
      }

      .wheel {
        width: 70% !important;
      }

      .game-content-wheel .resultPopup {
        margin-top: 0 !important;
      }
    }
  }
</style>
