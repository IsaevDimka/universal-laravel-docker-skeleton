<template>
  <card :title="$t('your_info')">
    <form @submit.prevent="update" @keydown="form.onKeydown($event)">
      <alert-success :form="form" :message="$t('info_updated')" />

      <!-- User ID -->
      <div class="form-group row">
        <label class="col-md-3 col-form-label text-md-right">ID</label>
        <div class="col-md-7">
          <input v-model="form.id" class="form-control" readonly>
        </div>
      </div>

      <!-- Email -->
      <div class="form-group row">
        <label class="col-md-3 col-form-label text-md-right">{{ $t('email') }}</label>
        <div class="col-md-7">
          <input v-model="form.email" :class="{ 'is-invalid': form.errors.has('email') }" class="form-control" type="email" name="email" readonly>
          <has-error :form="form" field="email" />
        </div>
      </div>

      <!-- Username -->
      <div class="form-group row">
        <label class="col-md-3 col-form-label text-md-right">{{ $t('username') }}</label>
        <div class="col-md-7">
          <input v-model="form.username" :class="{ 'is-invalid': form.errors.has('username') }" class="form-control" type="text" name="username" readonly>
          <has-error :form="form" field="username" />
        </div>
      </div>

      <!-- First name -->
      <div class="form-group row">
        <label class="col-md-3 col-form-label text-md-right">{{ $t('first_name') }}</label>
        <div class="col-md-7">
          <input v-model="form.first_name" :class="{ 'is-invalid': form.errors.has('first_name') }" class="form-control" type="text" name="first_name">
          <has-error :form="form" field="first_name" />
        </div>
      </div>

      <!-- Last name -->
      <div class="form-group row">
        <label class="col-md-3 col-form-label text-md-right">{{ $t('last_name') }}</label>
        <div class="col-md-7">
          <input v-model="form.last_name" :class="{ 'is-invalid': form.errors.has('last_name') }" class="form-control" type="text" name="last_name">
          <has-error :form="form" field="last_name" />
        </div>
      </div>

      <!-- Phone -->
      <div class="form-group row">
        <label class="col-md-3 col-form-label text-md-right">{{ $t('phone') }}</label>
        <div class="col-md-7">
          <input v-model="form.phone" :class="{ 'is-invalid': form.errors.has('phone') }" class="form-control" type="phone" name="phone">
          <has-error :form="form" field="phone" />
        </div>
      </div>

      <!-- Telegram Chat Id -->
      <div class="form-group row">
        <label class="col-md-3 col-form-label text-md-right">Telegram chat id</label>
        <div class="col-md-7">
          <input v-model="form.telegram_chat_id" :class="{ 'is-invalid': form.errors.has('telegram_chat_id') }" class="form-control" type="text" name="telegram_chat_id">
          <has-error :form="form" field="telegram_chat_id" />
        </div>
      </div>

      <!-- Locale -->
      <div class="form-group row">
        <label class="col-md-3 col-form-label text-md-right">{{ $t('locale') }}</label>
        <div class="col-md-7">
          <select v-model="form.locale" :class="{ 'is-invalid': form.errors.has('locale') }" class="custom-select">
            <option v-for="(value, key) in locales"
                    :key="key"
                    :label="`${key} (${value})`"
                    :value="key"
            >{{ value }}</option>
          </select>
          <has-error :form="form" field="locale" />
        </div>
      </div>

      <!-- Submit Button -->
      <div class="form-group row">
        <div class="col-md-9 ml-md-auto">
          <v-button :loading="form.busy" type="success">
            {{ $t('update') }}
          </v-button>
        </div>
      </div>
    </form>
  </card>
</template>

<script>
import Form from 'vform'
import { mapGetters } from 'vuex'

export default {
  scrollToTop: false,

  metaInfo () {
    return { title: this.$t('settings') }
  },

  data: () => ({
    form: new Form({
      id: '',
      username: '',
      first_name: '',
      last_name: '',
      email: '',
      phone: '',
      telegram_chat_id: '',
      locale: '',
    })
  }),

  computed: mapGetters({
    user: 'auth/user',
    locales: 'lang/locales'
  }),

  created () {
    // Fill the form with user data.
    this.form.keys().forEach(key => {
      this.form[key] = this.user[key]
    })
  },

  methods: {
    async update () {
      const { data } = await this.form.patch('/api/v1/settings/profile')

      this.$store.dispatch('auth/updateUser', { user: data })
    }
  }
}
</script>
