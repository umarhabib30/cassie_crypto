<script>
  import Bus from '../../bus';

  import qr from 'qrcode';
  import {mapGetters} from 'vuex';
  import WebIcon from "../ui/WebIcon.vue";
  import ManualDepositConfirmModal from "./ManualDepositConfirmModal.vue";
  import { startPicking } from "../../utils/depositPicker/DepositPickerManager.js";

  export default {
    methods: {
      open(tab = null) {
        Bus.$emit('modal:new', {
          name: 'user_wallet',
          component: {
            data() {
              return {
                tab: !tab ? 'deposit' : tab,
                promocode: '',
                buycrypto: 0,

                depositWallet: null,
                depositAmount: 100,
                withdrawWallet: '',
                withdrawAmount: '',

                depositDisable: false,

                disable: false,

                isHistory: false,
                historyTab: 'deposit',
                historyData: null,
                changellyWidgetUrl: '',
              }
            },
            watch: {
              isHistory() {
                if (this.isHistory) this.loadTab();
              },
              historyTab() {
                this.loadTab();
              },
              tab() {
                if (this.tab === 'buyCrypto') {
                  this.$store.commit('setCurrency', 'commerce_bitcoin');
                  this.loadTab();
                } else {
                  this.loadTab();
                }
              },
              currency() {
                this.loadTab();
              }
            },
            created() {
              if (this.tab === 'buyCrypto') {
                this.$store.commit('setCurrency', 'commerce_bitcoin');
                this.loadTab();
              } else {
                this.loadTab();
              }
            },
            computed: {
              ...mapGetters(['user', 'vip', 'currency', 'currencies']),
            },
            methods: {
              updateChangellyWidgetUrl() {
                const baseUrl = 'https://widget.changelly.com?from=eur&to=btc&amount=100';
                const address = this.depositWallet ? this.depositWallet : '';
                const params = `&address=${address}&fromDefault=eur&toDefault=btc&merchant_id=nc-E-Kmv02rd_U7E&payment_id=&v=3&type=no-rev-share&color=00dc82&headerId=2&logo=hide&buyButtonTextId=5&readOnlyDestinationAddress=true`;
                this.changellyWidgetUrl = `${baseUrl}${params}`;
              },
              makeDeposit() {
                  if(this.depositDisable) return;

                  if(isNaN(Math.floor(this.depositAmount))) {
                      this.$toast.error('Deposit sum is not a number');
                      return;
                  }
                  if(Math.floor(this.depositAmount) <= 0) {
                      this.$toast.error('Deposit sum must be positive value');
                      return;
                  }

                  Bus.$emit('support:deposit', {currency: this.currency, sum: this.depositAmount});
                  this.depositDisable = true;
                  Bus.$emit('modal:close');
              },
              makeWithdraw() {
              //  if(this.depositDisable) return;

                if(isNaN(Math.floor(this.withdrawAmount))) {
                    this.$toast.error('Withdraw sum is not a number');
                    return;
                }
                if(Math.floor(this.withdrawAmount <= 0)) {
                    this.$toast.error('Withdraw sum must be positive value');
                    return;
                }

                axios.post('/api/wallet/withdraw', {
                    currency: this.currency,
                    sum: this.withdrawAmount,
                }).then(({ data }) => {
                    Bus.$emit('support:withdraw', {id : data.id, currency : this.currency, sum: this.withdrawAmount});
                    this.depositDisable = true;
                    Bus.$emit('modal:close');
                }).catch((e) => {
                    this.$toast.error(e);
                });               
               },
              manualConfirm() {
                Bus.$emit('modal:close');
                ManualDepositConfirmModal.methods.open();
              },
              loadTab() {
                if(this.isHistory) {
                  this.historyData = null;

                  if(this.historyTab === 'deposit') {
                    axios.post('/api/wallet/history/deposits').then(({ data }) => {
                      this.historyData = data;
                    });
                  } else if(this.historyTab === 'withdraw') {
                    axios.post('/api/wallet/history/withdraws').then(({ data }) => {
                      this.historyData = data;
                    });
                  }
                } else {
                  if (this.tab === 'deposit' || this.tab === 'buyCrypto') {
                    if (this.currency.startsWith('local_')) return;

                    const canvas = document.createElement('canvas');
                    this.depositWallet = null;
                    let e = document.querySelector('#qr canvas');
                    if (e) e.remove();

                    window.axios.post('/api/wallet/getDepositWallet', {currency: this.currency}).then(({data}) => {
                      if (data.currency !== this.currency) return;
                      this.depositWallet = data.wallet;

                      setTimeout(() => {
                        let e = document.querySelector('#qr canvas');
                        if (e) e.remove();
                        qr.toCanvas(canvas, data.wallet);
                        document.querySelector('#qr').append(canvas);
                      });

                      startPicking(data.currency, data.wallet);
                      if (this.tab === 'buyCrypto') {
                        this.updateChangellyWidgetUrl();
                      }
                    });
                  }
                }
              },
              enterPromocode() {
                window.axios.post('/api/promocode/activate', {code: this.promocode}).then(() => {
                  this.promocode = '';
                  this.$toast.success(this.$i18n.t('bonus.promo.success'));
                }).catch((code) => {
                  if (code.response.data.code === 1) this.$toast.error(this.$i18n.t('bonus.promo.invalid'));
                  if (code.response.data.code === 2) this.$toast.error(this.$i18n.t('bonus.promo.expired_time'));
                  if (code.response.data.code === 3) this.$toast.error(this.$i18n.t('bonus.promo.expired_usages'));
                  if (code.response.data.code === 4) this.$toast.error(this.$i18n.t('bonus.promo.used'));
                  if (code.response.data.code === 5) this.$toast.error(this.$i18n.t('general.error.promo_limit'));
                  if (code.response.data.code === 7) this.$toast.error(this.$i18n.t('general.error.vip_only_promocode'));
                });
              },
              copy(text) {
                navigator.clipboard.writeText(text);
                this.$toast.success('Copied!');
              },
              accountCenter() {
                Bus.$emit('modal:close');
              },
              promoRedirect() {
                Bus.$emit('modal:close');
                this.$router.push('/bonus-50');
              },
              isNumber($event) {
                const allowed = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
                if (!allowed.includes($event.key)) $event.preventDefault();
              },
              cancelWithdraw(id) {
                axios.post('/api/wallet/cancel_withdraw', { id: id }).then(() => {
                  this.$toast.success(this.$i18n.t('wallet.history.withdraw_cancelled'));
                  this.loadTab();
                });
              },
              withdraw() {
                if (this.disable) return;
                if (!this.currency.startsWith('local_') && this.withdrawWallet.length < 4) {
                  this.$toast.error(this.$i18n.t('general.error.enter_wallet'));
                  return;
                }

                this.disable = true;
                window.axios.post('/api/wallet/withdraw', {
                  sum: parseFloat(this.withdrawAmount),
                  currency: this.currency,
                  wallet: this.withdrawWallet,
                  type: null
                }).then(() => {
                  this.disable = false;
                  Bus.$emit('modal:close');
                  this.$toast.success('Withdrawal request has been created.');
                }).catch((error) => {
                  switch (error.response.data.code) {
                    case 1:
                      this.$toast.error(this.$i18n.t('general.error.invalid_withdraw'));
                      break;
                    case 2:
                      this.$toast.error(this.$i18n.t('general.error.invalid_wager'));
                      break;
                    case 3:
                      this.$toast.error(this.$i18n.t('general.error.only_one_withdraw'));
                      break;
                  }
                  this.disable = false;
                });
              }
            },
            template: `
                  <div class="wallet-modal">
                    <div class="wallet-header">
                      <i class="fal fa-chevron-left" v-if="isHistory" @click="isHistory = false"></i>
                      {{ $t(!isHistory ? 'walletModal.title' : 'walletModal.titleHistory') }}
                      <div class="history" @click="isHistory = true">
                        <web-icon icon="time" class="history" v-if="!isHistory"></web-icon>
                        Geschiedenis
                      </div>
                    </div>
                    <div class="wallet-content">
                      <template v-if="isHistory">
                        <div class="history">
                          <div class="wallet-tabs">
                            <div class="tab" :class="historyTab === 'deposit' ? 'active' : ''" @click="historyTab = 'deposit'">
                              {{ $t('walletModal.historyTabs.deposits') }}
                            </div>
                            <div class="tab" :class="historyTab === 'withdraw' ? 'active' : ''" @click="historyTab = 'withdraw'">
                              {{ $t('walletModal.historyTabs.withdraws') }}
                            </div>
                          </div>
                          <loader v-if="!historyData"></loader>
                          <template v-else>
                            <overlay-scrollbars :options="{ scrollbars: { autoHide: 'leave' }, className: 'os-theme-thin-light' }">
                              <template v-if="historyTab === 'deposit'">
                                <div v-if="historyData.length === 0" class="nothingInHistory">
                                  <web-icon icon="time"></web-icon>
                                  <div>{{ $t('wallet.history.empty') }}</div>
                                </div>
                                <table class="live-table" v-else>
                                  <thead>
                                    <tr>
                                      <th>{{ $t('wallet.history.name') }}</th>
                                      <th>{{ $t('wallet.history.sum') }}</th>
                                      <th>{{ $t('wallet.history.date') }}</th>
                                      <th>{{ $t('wallet.history.status') }}</th>
                                    </tr>
                                  </thead>
                                  <tbody class="live_games">
                                    <tr v-for="deposit in historyData">
                                      <th>
                                        <div>
                                          <div>
                                            <web-icon :icon="currencies[deposit.currency].icon" :style="{ color: currencies[deposit.currency].style }"></web-icon>
                                            {{ currencies[deposit.currency].name }}
                                          </div>
                                        </div>
                                      </th>
                                      <th>
                                        {{ deposit.sum.$numberDecimal ? rawBitcoin(deposit.currency, parseFloat(deposit.sum.$numberDecimal)) : deposit.sum.toFixed(2) }} <web-icon :icon="currencies[deposit.currency].icon" :style="{ color: currencies[deposit.currency].style }"></web-icon>
                                      </th>
                                      <th>
                                        <div>{{ new Date(deposit.created_at).toLocaleString() }}</div>
                                      </th>
                                      <th>
                                        <div v-if="!deposit.aggregator">{{ deposit.confirmations }}/{{ currencies[deposit.currency].requiredConfirmations }} {{ $t('wallet.history.confirmations') }}</div>
                                        <div v-else>
                                          <template v-if="deposit.status === 0">{{ $t('wallet.history.not_paid') }}</template>
                                          <template v-if="deposit.status === 1">{{ $t('wallet.history.paid') }}</template>
                                        </div>
                                      </th>
                                    </tr>
                                  </tbody>
                                </table>
                              </template>
                              <template v-if="historyTab === 'withdraw'">
                                <div v-if="historyData.length === 0" class="nothingInHistory">
                                  <web-icon icon="time"></web-icon>
                                  <div>{{ $t('wallet.history.empty') }}</div>
                                </div>
                                <table class="live-table" v-else>
                                  <thead>
                                    <tr>
                                      <th>
                                        {{ $t('wallet.history.name') }}
                                      </th>
                                      <th>
                                        {{ $t('wallet.history.sum') }}
                                      </th>
                                      <th>
                                        {{ $t('wallet.history.date') }}
                                      </th>
                                      <th>
                                        {{ $t('wallet.history.status') }}
                                      </th>
                                    </tr>
                                  </thead>
                                  <tbody class="live_games">
                                    <tr v-for="withdraw in historyData">
                                      <th>
                                        <div>
                                          <div><web-icon :icon="currencies[withdraw.currency].icon" :style="{ color: currencies[withdraw.currency].style }"></web-icon> {{ currencies[withdraw.currency].name }}</div>
                                          <div data-highlight>{{ withdraw.address }}</div>
                                        </div>
                                      </th>
                                      <th>
                                        <div>
                                          {{ withdraw.sum }} <web-icon :icon="currencies[withdraw.currency].icon" :style="{ color: currencies[withdraw.currency].style }"></web-icon>
                                        </div>
                                      </th>
                                      <th>
                                        <div>
                                          {{ new Date(withdraw.created_at).toLocaleString() }}
                                        </div>
                                      </th>
                                      <th>
                                        <span v-if="withdraw.status === 0 || withdraw.status === 3">
                                            {{ $t('wallet.history.withdraw_status.moderation') }}
                                          <div v-if="withdraw.status === 0 && !withdraw.auto" data-highlight class="clickable" @click="cancelWithdraw(withdraw._id)">{{ $t('wallet.history.cancel') }}</div>
                                        </span>
                                        <span v-else-if="withdraw.status === 1">
                                          <div class="text-success">{{ $t('wallet.history.withdraw_status.accepted') }}</div>
                                        </span>
                                        <span v-else-if="withdraw.status === 2">
                                          <div class="text-danger">{{ $t('wallet.history.withdraw_status.declined') }}</div>
                                          <div data-highlight>{{ $t('wallet.history.withdraw_status.reason') }} {{ withdraw.decline_reason }}</div>
                                        </span>
                                        <span v-else-if="withdraw.status === 4">{{ $t('wallet.history.withdraw_status.cancelled') }}</span>
                                      </th>
                                    </tr>
                                  </tbody>
                              </table>
                              </template>
                            </overlay-scrollbars>
                          </template>
                        </div>
                      </template>

                      <div class="wallet-tabs">
                        <div class="tab" :class="tab === 'deposit' ? 'active' : ''" @click="tab = 'deposit'">
                          {{ $t('walletModal.tabs.deposit') }}
                        </div>
                        <div class="tab" :class="tab === 'buyCrypto' ? 'active' : ''" @click="tab = 'buyCrypto'">
                          {{ $t('walletModal.tabs.buyCrypto') }}
                        </div>
                        <div class="tab" :class="tab === 'withdraw' ? 'active' : ''" @click="tab = 'withdraw'">
                          {{ $t('walletModal.tabs.withdraw') }}
                        </div>
                        <div class="tab" :class="tab === 'promocode' ? 'active' : ''" @click="tab = 'promocode'">{{ $t('walletModal.tabs.promocode') }}</div>
                      </div>
                      <template v-if="tab === 'deposit'">
                        <div class="deposit-currencies">
                          <dropdown :entries="Object.keys(currencies).map(e => currencies[e])"
                            :onSelect="(e) => { $store.dispatch('setCurrency', e.id) }" :select="currency"></dropdown>

                          <dropdown v-if="currencies[currency].chains.length > 0"
                            :entries="currencies[currency].chains.map(e => { return { name: e.name, id: e.id } })"
                            :onSelect="(e) => { chain = e; loadTab(); }"></dropdown>
                        <template v-if="!currency.startsWith('local_')">
                          <div class="deposit-address">
                            <div class="text">{{ $t('walletModal.address') }}</div>
                            <div class="v">
                              <input placeholder="Address" readonly
                                     :value="!depositWallet ? $t('walletModal.loading') : depositWallet">
                              <button class="btn btn-primary" @click="depositWallet ? copy(depositWallet) : false"
                                      :disabled="!depositWallet"><i class="fal fa-copy"></i>
                              </button>
                            </div>
                          </div>
                          <div class="qr" v-if="depositWallet">
                            <div id="qr"></div>
                          </div>
                        </template>
                        <template v-else>
                            <div class="divider">
                                    <div class="line"></div>
                                    <i class="fal fa-angle-down"></i>
                                    <div class="line"></div>
                                </div>
                                <div class="walletOut">
                                    <input type="text" v-model="depositAmount">
                                    <button :class="'btn btn-primary ' + (depositDisable ? 'disabled' : '')" @click="makeDeposit()" :disabled="depositDisable">{{ $t('wallet.deposit.go') }}</button>
                                </div>
                        </template>
                        <div class="status">
                          <p><i class="fal fa-info-circle"></i> {{ $t('walletModal.depositInfo') }}</p>
                          <!---<a href="javascript:void(0)" @click="manualConfirm">Klik hier als uw storting niet is weergegeven na 5 minuten.</a>--->
                        </div>
                        </div>
                      </template>
                      <template v-if="tab === 'buyCrypto'">
                        <div class="buy-crypto">
                          <iframe :src="changellyWidgetUrl" width="100%" height="100%" frameborder='none' allow="camera">Can't load widget</iframe>  
                          <p><i class="fal fa-info-circle"></i> {{ $t('walletModal.buyCryptoInfo') }}</p>
                        </div>
                      </template>
                      <template v-if="tab === 'withdraw'">
                        <div class="deposit-currencies">
                          <dropdown :entries="Object.keys(currencies).map(e => currencies[e])"
                            :onSelect="(e) => { $store.dispatch('setCurrency', e.id) }" :select="currency"></dropdown>

                          <dropdown v-if="currencies[currency].chains.length > 0"
                            :entries="currencies[currency].chains.map(e => { return { name: e.name, id: e.id } })"
                            :onSelect="(e) => { chain = e; loadTab(); }"></dropdown>
                        </div>

                        <div class="withdraw-address" v-if="!currency.startsWith('local_')">
                          <div class="text">{{ $t('walletModal.withdrawAddress') }}</div>
                          <div class="v">
                            <input :placeholder="$t('walletModal.withdrawAddress')" v-model="withdrawWallet">
                          </div>
                        </div>
                        <div class="withdraw-address">
                          <div class="text">{{ $t('walletModal.withdrawAmount') }}</div>
                          <div class="v">
                            <input :placeholder="$t('walletModal.withdrawAmount')" v-model="withdrawAmount" type="number">
                          </div>
                        </div>
                        <div class="notes">
                          <div class="title">{{ $t('walletModal.withdrawNotes.title') }}</div>
                          <ul>
                            <li>
                              {{ $t('walletModal.withdrawNotes.1', {amount: vip.filter(e => e.level === user.user.vipLevel)[0].numberOfWithdrawals}) }}
                            </li>
                            <li>
                              {{ $t('walletModal.withdrawNotes.2', {fee: vip.filter(e => e.level === user.user.vipLevel)[0].withdrawFee.toFixed(2)}) }}
                            </li>
                            <li>
                              {{ $t('walletModal.withdrawNotes.4', {min: '€' + currencies[currency].minWithdraw}) }}
                            </li>
                            <li>
                              {{ $t('walletModal.withdrawNotes.5', {max: '€' + vip.filter(e => e.level === user.user.vipLevel)[0].maxWithdrawal.toFixed(currency.includes('local') ? 2 : 0)})}}
                            </li>
                          </ul>
                        </div>
                        <button v-if="!currency.startsWith('local_')"  class="btn btn-primary btn-block" @click="withdraw" :disabled="disable">
                          {{ $t('walletModal.withdraw') }}
                        </button>
                        <button v-else class="btn btn-primary btn-block" @click="makeWithdraw" :disabled="disable">
                          {{ $t('walletModal.withdraw') }}
                        </button>
                      </template>
                      <template v-if="tab === 'promocode'">
                        <input :placeholder="$t('walletModal.tabs.promocode')" v-model="promocode">
                        <button class="btn btn-primary btn-block" @click="enterPromocode">{{ $t('walletModal.enter') }}</button>
                      </template>
                    </div>
                  </div>`
          }
        });
      }
    },
    components: {
      WebIcon
    }
  }
</script>

<style lang="scss">
  @import "resources/sass/variables";

  .xmodal.user_wallet {
    width: 650px;
    max-width: 90%;
    border-radius: 15px !important;

    @media(max-width: 750px) {
      width: 100% !important;
    }

    .info-row {
      display: flex;
      width: 100%;

      .withdraw-address:first-child {
        margin-right: 10px;
      }
    }

    .quick {
      display: flex;
      flex-wrap: wrap;
      margin-top: 15px;

      .option {
        border-radius: 100px;
        border: 2px solid #405272;
        padding: 5px 15px;
        margin: 3px;
        display: flex;
        align-items: center;
        background: transparent;
        cursor: pointer;
        transition: background .3s ease;
        margin-bottom: 10px;

        &:hover {
          background: #405272;
        }

        i, svg {
          margin-right: 10px;
        }
      }
    }

    .notes {
      margin-top: 10px;

      .title {
        font-size: 1.1em;
        font-weight: 600;
        margin-bottom: 10px;
      }

      ul {
        padding-left: 30px;
        opacity: .6;
        font-size: 0.8em;
      }
    }

    .withdraw-address {
      font-weight: 600;
      margin-top: 15px;
      margin-bottom: 5px;
      width: 100%;

      .v {
        display: flex;
        width: 100%;

        select {
          height: 45px;
          padding: 0 15px;
          background: #354460;
          color: #90A3C7;
          border: none;
          border-radius: 10px;
        }

        select, input {
          width: 100%;
          margin-top: 10px;
        }
      }
    }

    .deposit-address {
      width: 100%;
      .text {
        font-weight: 600;
        margin-bottom: 5px;
        margin-top: 15px;
      }

      .v {
        display: flex;
        position: relative;
        box-shadow: inset 0 0 #fff, inset 0 0 0 1px #44403c, 0 1px 2px #0000000d;
        border-radius: .75rem;

        input {
          width: 100%;
          background-color: transparent;
          border: none;
          box-shadow: none;
          white-space: nowrap;
          overflow: hidden !important;
          text-overflow: ellipsis;

          &.padding {
            padding-left: 40px;
          }


          @media(max-width: 750px) {
            padding: 0px 10px;
          }
        }

        .btn {
          margin: unset !important;
          justify-content: center;
          display: flex;
          padding: 15px;
          border-radius: .75rem;
        }
      }
    }

    .qr {
      display: flex;
      align-items: center;
      justify-content: center;
      margin-top: 25px;
      background: white;
      width: fit-content;
      margin-left: auto;
      margin-right: auto;
      margin-bottom: 15px;

      #qr {
        display: flex;
      }
    }

    .status {
      font-size: 11px;
      margin-top: 15px;
      p {
        margin: 0px;
      }
    }

    .buy-crypto {
      p {
        font-size: 11px;
        margin-top: 10px;
      }
      
      iframe {
        border-radius: .75rem;
        height: 390px;
      }
    }

    .deposit-currencies {
      display: flex;
      flex-wrap: wrap;
      margin-bottom: 15px;
      align-items: center;
      justify-content: center;

      .currency {
        margin-right: 10px;
        cursor: pointer;
        transition: background .3s ease, color .3s ease;

        @include themed() {
          background: t('input');
          color: t('secondary');
        }

        border-radius: 6px;
        font-weight: 600;
        padding: 5px 15px;

        &:last-child {
          margin-right: 0;
        }

        &.active {
          color: #324058;
          @include themed() {
            background: t('secondary');
          }
        }
      }
    }

    .btn {
      margin-top: 15px;
      padding: 15px 50px;
      border-radius: 15px !important;
    }

    .modal_content {
      padding: 0 !important;
      border-radius: 15px !important;

      .wallet-header {
        font-weight: 600;
        font-size: 1.2em;
        padding: 15px 25px;
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
        display: flex;
        align-items: center;

        svg, i {
          opacity: .5;
          transition: opacity .3s ease;
          cursor: pointer;
          margin-right: 0px !important;

          &:hover {
            opacity: 1;
          }

          &.fa-chevron-left {
            margin-right: 15px !important;
          }
        }

        .history {
          margin-left: auto;
          margin-right: 20px;
          cursor: pointer;
        }
      }

      .wallet-content {
        padding: 15px;
        position: relative;

        .history {
          position: absolute;
          left: 0;
          top: 0;
          width: 100%;
          height: 100%;
          z-index: 5;
          padding: 15px;
          display: flex;
          flex-direction: column;

          .nothingInHistory {
            margin-top: 25px;
            text-align: center;

            i, svg {
              font-size: 2em;
              margin-bottom: 10px;
            }
          }

          .os-host {
            height: 100%;
          }

          .loaderContainer {
            margin-top: auto;
          }

          @include themed() {
            background: t('modal');
          }
        }

        .wallet-tabs {
          @include themed() {
            background: t('block');
          }

          display: flex;
          gap: 3px;
          margin-bottom: 25px;
          border-radius: .75rem;
          overflow: hidden;
          box-shadow: inset 0 0 #fff, inset 0 0 0 1px #44403c, 0 1px 2px #0000000d;
          padding: 5px;
          position: relative;

          @media(max-width: 750px) {
            flex-direction: column;
          }

          .tab {
            padding: 7px 12px;
            background: transparent;
            //transition: background .3s ease, color .3s ease;
            //width: 100%;
            //border-radius: .75rem;
            cursor: pointer;
            text-align: center;
            align-content: center;
            flex: auto;
            color: #b7b7b7;
            border-radius: .75rem;
            //box-shadow: inset 0 0 #fff, inset 0 0 0 1px #44403c, 0 1px 2px #0000000d;
            
            &:hover {
              background: #292524;
              color: #fff;
            }

            &.active {
              /*@include themed() {
                background: t('secondary') !important;
              }
              color: black;*/
              background: #292524;
              color: #fff;

            }
          }
        }
      }
    }
  }
</style>
