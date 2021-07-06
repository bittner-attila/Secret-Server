<template>  
  <v-row justify="center" align="center">
    <v-col cols="12" sm="8" md="6">
      <v-card class="logo py-4 d-flex justify-center">
    <form @submit.prevent="submit" class="col-6" ref="secretForm"> 
      <h1 v-text="title"></h1>          
        <v-textarea
          v-model="secret"
          label="Secret"
          required
          :rules="[required]"
        ></v-textarea>     

        <v-text-field
          :rules="[required, greaterThan(expireAfterViews, 0)]"
          v-model="expireAfterViews"
          label="expire after views"
          required
        ></v-text-field> 

        <v-text-field
          v-model="expireAfter"
          label="expire after (0 means never expires)"
          suffix="min"
          required
        ></v-text-field>  
      <v-btn
        class="mr-4"
        type="submit"
      >
        submit
      </v-btn>
      <v-btn @click="clear">
        clear
      </v-btn>
    </form>
    <v-dialog
      v-model="dialog"
      width="500"
    >
      <template v-slot:activator="{ on, attrs }"></template>

      <v-card>
        <v-card-title  class="text-h5 grey lighten-2">
          {{ dialogData.title }}
        </v-card-title>

        <v-card-text>
          {{ dialogData.message }}
        </v-card-text>

        <v-divider></v-divider>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="primary"
            text
            @click="clear"
          >
            Ok
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-card>
</v-col>
</v-row>
</template>

<script>
  export default {
    data: () => ({
      title: 'Create Secret',
      secret: '',
      expireAfter: null,
      expireAfterViews: null,
      dialog: false,
      dialogData: {
        title: '',
        message: '',
      },
      required(input) {
        return  input && input.length > 0 || 'This field is required!';
      },
      greaterThan(input, number) {
        return  input > 0 || `The number must greater than ${number}!`;
      }
    }),

    methods: {
      submit () {
        let self = this;

        if (this.$refs[`secretForm`][0].validate()) {
          const newSecretData = { 
            secret: this.secret,
            expireAfter: this.expireAfter,
            expireAfterViews: this.expireAfterViews,
          };

          this.$axios.$post(process.env.backendURL + '/api/secret', newSecretData)
            .then(function(response){
              self.secretCreated(response.data.hash);            
            })
            .catch(error => {
              this.errorMessage = error.message;
              console.error("There was an error!", error);
            });
        }
        
          
      },
      secretCreated (hash) {
        this.dialogData.title = 'Secret Created!';
        this.dialogData.message = `Secret successfully created! You can share the secret with this key: ${hash}`;
        this.dialog = true;
      },
      clear () {
        this.secret = ''
        this.expireAfter = null
        this.expireAfterViews = null
        this.dialogData.hash = ''
        this.dialogData.title = ''
        this.dialogData.message = ''
        this.dialog = false
        this.$refs.secretForm.reset()
      },
    },
  }
</script>