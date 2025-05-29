<template>
  <div class="container">
    <h1>{{ $t('general.privacy_policy.title') }}</h1>
    <p>{{ $t('general.privacy_policy.introduction') }}</p>

    <h2>{{ $t('general.privacy_policy.sections.data_collection.title') }}</h2>
    <p>{{ $t('general.privacy_policy.sections.data_collection.description') }}</p>
    <ul>
      <li>
        <strong>{{ $t('general.privacy_policy.sections.data_collection.items.personal_data.title') }}:</strong>
        {{ $t('general.privacy_policy.sections.data_collection.items.personal_data.description') }}
      </li>
      <li>
        <strong>{{ $t('general.privacy_policy.sections.data_collection.items.transaction_data.title') }}:</strong>
        {{ $t('general.privacy_policy.sections.data_collection.items.transaction_data.description') }}
      </li>
      <li>
        <strong>{{ $t('general.privacy_policy.sections.data_collection.items.technical_data.title') }}:</strong>
        {{ $t('general.privacy_policy.sections.data_collection.items.technical_data.description') }}
      </li>
    </ul>

    <h2>{{ $t('general.privacy_policy.sections.data_use.title') }}</h2>
    <p>{{ $t('general.privacy_policy.sections.data_use.description') }}</p>
    <ul>
      <li>{{ $t('general.privacy_policy.sections.data_use.items[0]') }}</li>
      <li>{{ $t('general.privacy_policy.sections.data_use.items[1]') }}</li>
      <li>{{ $t('general.privacy_policy.sections.data_use.items[2]') }}</li>
      <li>{{ $t('general.privacy_policy.sections.data_use.items[3]') }}</li>
      <li>{{ $t('general.privacy_policy.sections.data_use.items[4]') }}</li>
    </ul>

    <h2>{{ $t('general.privacy_policy.sections.data_protection.title') }}</h2>
    <p>{{ $t('general.privacy_policy.sections.data_protection.description') }}</p>

    <h2>{{ $t('general.privacy_policy.sections.data_sharing.title') }}</h2>
    <p>{{ $t('general.privacy_policy.sections.data_sharing.description') }}</p>
    <ul>
      <li>{{ $t('general.privacy_policy.sections.data_sharing.items[0]') }}</li>
      <li>{{ $t('general.privacy_policy.sections.data_sharing.items[1]') }}</li>
      <li>{{ $t('general.privacy_policy.sections.data_sharing.items[2]') }}</li>
    </ul>

    <h2>{{ $t('general.privacy_policy.sections.your_rights.title') }}</h2>
    <p>{{ $t('general.privacy_policy.sections.your_rights.description') }}</p>
    <ul>
      <li>{{ $t('general.privacy_policy.sections.your_rights.items[0]') }}</li>
      <li>{{ $t('general.privacy_policy.sections.your_rights.items[1]') }}</li>
      <li>{{ $t('general.privacy_policy.sections.your_rights.items[2]') }}</li>
      <li>{{ $t('general.privacy_policy.sections.your_rights.items[3]') }}</li>
    </ul>

    <h2>{{ $t('general.privacy_policy.sections.cookies.title') }}</h2>
    <p>{{ $t('general.privacy_policy.sections.cookies.description') }}</p>

    <h2>{{ $t('general.privacy_policy.sections.policy_changes.title') }}</h2>
    <p>{{ $t('general.privacy_policy.sections.policy_changes.description') }}</p>

    <h2>{{ $t('general.privacy_policy.sections.contact.title') }}</h2>
    <p>{{ $t('general.privacy_policy.sections.contact.description') }}</p>
  </div>
</template>

<script>
export default {
  name: 'PrivacyPolicy'
};
</script>
