<template>
  <span class="unit">{{ formattedComputedValue }}</span>
</template>

<script>
  import bitcoin from 'bitcoin-units';
  import { mapGetters } from 'vuex';

  export default {
    computed: {
      computedValue() {
        if (this.to.startsWith('local_')) return this.value ? this.value : 0;
        if (this.fiat) return this.tokenToFiat(this.currencies[this.to].price, this.value ? this.value : 0);

        return bitcoin(this.value ? this.value : 0, 'btc').to(this.unit).value();
      },
      formattedComputedValue() {
        let value = this.computedValue;
        if (this.to.startsWith('local_')) {
          return this.formatCurrency(value);
        }
        if (this.fiat) {
          return '$' + this.formatCurrency(value);
        }
        return this.formatNumber(value.toFixed(this.unit === 'satoshi' ? 0 : 8));
      },
      ...mapGetters(['unit'])
    },
    methods: {
      formatCurrency(value) {
        return new Intl.NumberFormat('en-US', {
          minimumFractionDigits: 2,
          maximumFractionDigits: 2
        }).format(value);
      },
      formatNumber(value) {
        return new Intl.NumberFormat('en-US', {
          minimumFractionDigits: 0,
          maximumFractionDigits: 8
        }).format(value);
      }
    },
    props: ['value', 'to', 'fiat']
  }
</script>
