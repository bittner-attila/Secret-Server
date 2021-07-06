<template>  
  <v-row justify="center" align="center">
    <v-col cols="12" sm="8" md="6">
      <v-card class="logo py-4 d-flex justify-center">
        <form @submit.prevent="submit" class="col-6"> 
          <h1 v-text="title"></h1>     
            
            <v-text-field
              v-model="hash"
              :counter="36"
              label="key"
              required
            ></v-text-field>  

          <v-btn
            class="mr-4"
            type="submit"
          >
            Find
          </v-btn>
          <v-btn @click="clear">
            clear
          </v-btn>
        </form>
      </v-card>
      <secret v-if="secret" 
        :hash="secret.hash"     
        :secret-text="secret.secretText"
        :created-at="secret.createdAt"
        :expires-at="secret.expiresAt"
        :remaining-views="secret.remainingViews">    
      </secret>
      <h1 v-if="secretNotFound" align="center">Sorry This Secret Is Gonna Be Secret.</h1>
</v-col>
</v-row>
</template>

<script>
import Secret from "./../../components/Secret.vue"

  export default {
    components: {
      Secret
    },
    data: () => ({
      title: 'Find Secret',
      secret: null,
      hash: '',
      expireAfter: null,
      expireAfterViews: null,
      secretNotFound: false,
    }),

    methods: {
      submit () {
        let self = this;
        this.$axios.$get(process.env.backendURL + '/api/secret/' + this.hash)
        .then(function(response){
            self.showSecret(response);            
          })
          .catch(error => {
            self.showSecretNotFound();
          });
      },
      showSecret(response) {
        this.secret = {
          hash:         response.data.hash,
          secretText:   response.data.secretText,
          createdAt:    response.data.createdAt,
          expiresAt:    response.data.expiresAt,
          remainingViews: response.data.remainingViews
        };
      },
      showSecretNotFound() {
        this.clear();
        this.secretNotFound = true;
      },
      clear () {
        this.hash = null
        this.secret = null
        this.expireAfter = null
        this.expireAfterViews = null
      },
    },
  }
</script>