<template>
  <div class="container">
    <h1>{{ $t('general.welcome_bonus.title') }}</h1>
    <ul>
      <li>{{ $t('general.welcome_bonus.conditions[0]') }}</li>
      <li>{{ $t('general.welcome_bonus.conditions[1]') }}</li>
      <li>{{ $t('general.welcome_bonus.conditions[2]') }}</li>
      <li>{{ $t('general.welcome_bonus.conditions[3]') }}</li>
      <li>{{ $t('general.welcome_bonus.conditions[4]') }}</li>
      <li>{{ $t('general.welcome_bonus.conditions[5]') }}</li>
    </ul>
  </div>
</template>

<script>
export default {
  name: 'WelcomeBonus'
};
</script>