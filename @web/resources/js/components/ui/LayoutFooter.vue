<template>
  <footer>
    <div class="gradient-border">
      <div class="gradient-container">
        <div class="border-divider"></div>
      </div>
      <div class="gradient-divider"></div>
    </div>
    <div class="container">
      <div class="footer">
        <div class="column grid">
          <div class="links">
            <router-link class="name" to="/">CASINO.DEMOCASINO.CLICK</router-link>
            <div class="link-group">
              <router-link to="/games">{{ $t('general.head.games') }}</router-link>
              <router-link to="/recommended-games">{{ $t('general.sidebar.recommended') }}</router-link>
              <router-link to="/slot-games">{{ $t('general.sidebar.slot') }}</router-link>
              <router-link to="/live-games">{{ $t('general.sidebar.live') }}</router-link>
            </div>
          </div>
          <div class="links">
            <div class="name">{{ $t('footer.features.more') }}</div>
            <div class="link-group">
              <router-link to="/privacyPolicy">{{ $t('footer.features.privacy') }}</router-link>
              <router-link to="/terms">{{ $t('footer.features.terms') }}</router-link>
            </div>
          </div>
          <div class="links">
            <div class="name">{{$t('general.recommended_games')}}</div>
            <div class="link-group">
              <router-link v-for="game in recommendedGames" :key="game.id" :to="`/casino/game/` + game.id" class="game">
                {{ game.name }}
              </router-link>
            </div>
          </div>
          <div class="links">
            <div class="name">Providers</div>
            <div class="link-group">
              <router-link 
                v-for="provider in providers" 
                :key="provider" 
                :to="`/casino/game/provider/${provider}`" 
                class="provider"
              >
                {{ provider }}
              </router-link>
            </div>
          </div>
          <div class="links">
            <div class="name">Socials</div>
            <div class="link-group">
              <a href="mailto:info@casino.democasino.click" target="_blank" rel="noopener noreferrer">info@cassie.bet</a>
              <a href="https://t.me/Athenian" target="_blank" rel="noopener noreferrer">Telegram</a>
              <!--<a href="https://x.com/cassie_bet" target="_blank" rel="noopener noreferrer">X</a>-->
              <!--<a href="https://www.instagram.com/cassie.bet_nl" target="_blank" rel="noopener noreferrer">Instagram</a>-->
            </div>
          </div>
        </div>
        <div>
          <div class="notes">
            <div>
              <img src="/img/misc/18.svg" alt="">
              <a href="https://kansspelautoriteit.nl/veilig-spelen/gokproblemen/" target="_blank"><img src="/img/misc/cruks.svg" width="100" alt=""></a>
              <a href="https://www.loketkansspel.nl" target="_blank"><img src="/img/misc/loket-kansspel.svg" width="160" alt=""></a>
              <img src="/img/misc/ssl.svg" alt="">
            </div>
            <div>
              <img src="/img/misc/bitcoin.svg" width="40" alt="">
              <img src="/img/misc/bitcoin-cash.svg" width="40" alt="">
              <img src="/img/misc/ethereum.svg" width="40" alt="">
              <img src="/img/misc/litecoin.svg" width="40" alt="">
              <img src="/img/misc/usdc.svg" width="40" alt="">
              <img src="/img/misc/doge.svg" width="40" alt="">
              <img src="/img/misc/ideal.png" width="40" alt="">
              <img src="/img/misc/visa.svg" width="40" alt="">
              <img src="/img/misc/mastercard.svg" width="40" alt="">
              <img src="/img/misc/maestro.svg" width="40" alt="">
              <img src="/img/misc/apple-pay.svg" width="40" alt="">
              <img src="/img/misc/google-pay.svg" width="40" alt="">
              <img src="/img/misc/moonpay.png" width="70" alt="">
            </div>
          </div>
        </div>
        <div class="column">
          <div class="license">
            <img src="/img/misc/cgf.png" width="100" alt="">
            <img src="/img/misc/IBIA.svg" width="100" alt="">
          </div>
        </div>
        <div class="column">
          <div class="copy">
            <p>{{ $t('footer.legal') }}</p>
            <p>casino.democasino.click is owned and operated by Santeda International B.V. registration number: 151296, registered address: Zuikertuintjeweg z/n (zuikertuin tower), Willemstad, Curacao. Santeda International B.V. is pending application by the GCB.</p>
          </div>
        </div>
      </div>
    </div>
  </footer>
</template>

<script>
  import { mapGetters } from 'vuex';
  import TermsModal from "../modals/TermsModal.vue";

  export default {
    computed: {
      ...mapGetters(['isGuest', 'currencies', 'games']),
      recommendedGames() {
        const gameIds = ["external:fc:vs20fruitsw", "external:fc:1hl65ce1lxuqdrkr", "external:fc:bigbassnewbb1320", "external:fc:vs20sugarrush", "external:fc:spacemanyxe123nh", "external:fc:vs20olympx", "external:fc:md500q83g7cdefw1", "external:fc:vs20gembondx", "external:fc:vswaysdogs", "external:fc:LightningTable01", "external:fc:h22z8qhp17sa0vkh", "external:fc:vs20goldfever", "external:fc:vs10fisheye", "external:fc:vswaystut", "external:fc:FunkyTime0000001", "external:fc:vswayszombcarn", "external:fc:k2oswnib7jjaaznw", "external:fc:vs20gobnudge", "external:fc:vswaysbbb", "external:fc:7nyiaws9tgqrzaz3", "external:fc:vswayslight", "external:fc:GoldVaultRo00001", "external:fc:vs20candyblitz", "external:fc:SweetSugar", "external:fc:vs20starlightx", "external:fc:5bzl2835s5ruvweg", "external:fc:Monopoly00000001", "external:fc:CrazyTime0000001", "external:fc:vs20fruitparty", "external:fc:15_golden_eggs", "external:fc:r20speedrtwo201s", "external:fc:vs10starpirate", "external:fc:1hl323e1lxuqdrkr", "external:fc:vs25goldparty", "external:fc:jzbzy021lg8xy9i2", "external:fc:pbvzrfk1fyft4dwe", "external:fc:vswaysrsm", "external:fc:oytmvb9m1zysmc44", "external:fc:vs243queenie", "external:fc:vs40demonpots", "external:fc:TopCard000000001", "external:fc:vs10dyndigd", "external:fc:228001", "external:fc:vs20bnnzdice", "external:fc:vs20procount", "external:fc:vs40wildwest"];
        /*
        const filteredGames = this.games.filter(game => gameIds.includes(game.id));
        const uniqueGames = Array.from(new Set(filteredGames.map(game => game.id))).map(id => filteredGames.find(game => game.id === id));
        return uniqueGames.sort((a, b) => gameIds.indexOf(a.id) - gameIds.indexOf(b.id));
        */
        const filteredGames = this.games.filter(game => gameIds.includes(game.id));
        const uniqueGames = Array.from(new Set(filteredGames.map(game => game.id))).map(id => filteredGames.find(game => game.id === id));
        for (let i = uniqueGames.length - 1; i > 0; i--) {
          const j = Math.floor(Math.random() * (i + 1));
          [uniqueGames[i], uniqueGames[j]] = [uniqueGames[j], uniqueGames[i]];
        }
        return uniqueGames.slice(0, 15);
      },
      providers() {
      return this.games
        ? [...new Set(this.games.filter(game => !game.isHidden).map(game => game.type))].sort()
        : [];
      },
    },
    methods: {
      openTerms(type) {
        TermsModal.methods.open(type);
      }
    }
  }
</script>

<style lang="scss">
  @import "resources/sass/variables";

  footer {
    @include themed() {
      background: rgba(28, 25, 23, .75);
      backdrop-filter: blur(10px);
      margin: 20px;
      border-radius: .75rem;
      overflow: hidden;
      margin-top: 100px;
      box-shadow: inset 0 0 #fff, inset 0 0 0 1px #44403c, 0 1px 2px #0000000d;

      .footer {
        display: flex;
        flex-direction: column;
        padding: 0 50px;

        .column {
          display: flex;
          width: 100%;
          align-items: center;
          padding: 25px 0;

          .license {
            display: flex;
            gap: 15px;
            align-items: center;
            width: 100%;
          }

          &.payment {
            img {
              width: 0;
              flex: 1;
            }

            @media(max-width: 991px) {
              display: none;
            }
          }

          &.grid {
            display: grid;
            grid-gap: 15px;
            gap: 15px;
            grid-template-columns: auto auto auto auto auto;
            align-items: flex-start;
            padding: 40px 0;

            @media(max-width: 1456px) {
              grid-template-columns: auto auto;
            }

            @media(max-width: 991px) {
              display: flex;
            }
          }

          .notes {
            display: flex;
            flex-direction: column;

            @media(max-width: 991px) {
              margin-top: 10px;
            }

            i {
              width: 36px;
              height: 36px;
              border: 2px solid #fff;
              border-radius: 50%;
              text-align: center;
              line-height: 32px;
              font-style: normal;
              font-weight: 600;
              font-size: 13px;
              margin-bottom: 15px;
            }

            small {
              font-size: 11px;
              opacity: .4;
            }
          }

          .links {
            display: flex;
            flex-direction: column;

            .name {
              text-transform: uppercase;
              margin-bottom: 15px;
              opacity: .6;
              font-weight: 600;
            }

            .link-group {
              display: flex;
              flex-direction: column;

              a {
                opacity: .4;
                margin-bottom: 5px;
                transition: opacity .3s ease;

                &:last-child {
                  margin-bottom: 0;
                }

                &:hover {
                  opacity: 1;
                }
              }
            }
          }

          .social-icons {
            display: flex;

            .social-icon {
              margin-right: 10px;
              border-radius: 10px;
              background: t('block');
              padding: 5px 10px;
              transition: background .3s ease, color .3s ease;
              cursor: pointer;

              &:hover {
                color: white;
                background: t('block');
              }

              &:last-child {
                margin-right: 0;
              }
            }
          }

          .copy {
            opacity: .3;
            font-size: 11px;
          }
        }
      }
    }

    .notes {
      div {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        align-items: center;
        justify-content: center;
        margin: 20px 0px;
      }
    } 
  }

  @include media_breakpoint_down(md) {
    footer .footer {
      padding: 0 15px 50px !important;

      .column {
        flex-direction: column;

        .copy {
          text-align: center;
          margin-bottom: 5px;
        }
      }
    }
  }
</style>
