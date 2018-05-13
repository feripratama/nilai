<template>
  <div class="card">
    <div class="card-header">
      <i class="fa fa-table" aria-hidden="true"></i> {{ title }}

      <div class="btn-group pull-right" role="group" style="display:flex;">
        <button class="btn btn-info btn-sm" role="button" @click="view">
          <i class="fa fa-eye" aria-hidden="true"></i>
        </button>
        <button class="btn btn-primary btn-sm" role="button" @click="back">
          <i class="fa fa-arrow-left" aria-hidden="true"></i>
        </button>
      </div>
    </div>

    <div class="card-body">
      <vue-form class="form-horizontal form-validation" :state="state" @submit.prevent="onSubmit">
        <div class="form-row mt-4">
          <div class="col-md">
            <validate tag="div">
              <label for="nomor_un">Siswa</label>
              <v-select name="nomor_un" v-model="model.siswa" :options="siswa" placeholder="Siswa" required autofocus disabled></v-select>

              <field-messages name="nomor_un" show="$invalid && $submitted" class="text-danger">
                <small class="form-text text-success">Looks good!</small>
                <small class="form-text text-danger" slot="required">Siswa is a required field</small>
              </field-messages>
            </validate>
          </div>
        </div>

        <div class="form-row mt-4">
          <div class="col-md">
            <validate tag="div">
              <label for="bahasa_indonesia">B. Indonesia</label>
              <input type="text" class="form-control" name="bahasa_indonesia" v-model="model.bahasa_indonesia" placeholder="B. Indonesia" required autofocus>

              <field-messages name="bahasa_indonesia" show="$invalid && $submitted" class="text-danger">
                <small class="form-text text-success">Looks good!</small>
                <small class="form-text text-danger" slot="required">B. Indonesia is a required field</small>
              </field-messages>
            </validate>
          </div>
        </div>

        <div class="form-row mt-4">
          <div class="col-md">
            <validate tag="div">
              <label for="bahasa_inggris">B. Inggris</label>
              <input type="text" class="form-control" name="bahasa_inggris" v-model="model.bahasa_inggris" placeholder="B. Inggris" required>

              <field-messages name="bahasa_inggris" show="$invalid && $submitted" class="text-danger">
                <small class="form-text text-success">Looks good!</small>
                <small class="form-text text-danger" slot="required">B. Inggris is a required field</small>
              </field-messages>
            </validate>
          </div>
        </div>

        <div class="form-row mt-4">
          <div class="col-md">
            <validate tag="div">
              <label for="matematika">Matematika</label>
              <input type="text" class="form-control" name="matematika" v-model="model.matematika" placeholder="Matematika" required>

              <field-messages name="matematika" show="$invalid && $submitted" class="text-danger">
                <small class="form-text text-success">Looks good!</small>
                <small class="form-text text-danger" slot="required">Matematika is a required field</small>
              </field-messages>
            </validate>
          </div>
        </div>

        <div class="form-row mt-4">
          <div class="col-md">
            <validate tag="div">
              <label for="ipa">IPA</label>
              <input type="text" class="form-control" name="ipa" v-model="model.ipa" placeholder="IPA" required>

              <field-messages name="ipa" show="$invalid && $submitted" class="text-danger">
                <small class="form-text text-success">Looks good!</small>
                <small class="form-text text-danger" slot="required">IPA is a required field</small>
              </field-messages>
            </validate>
          </div>
        </div>

        <div class="form-row mt-4">
          <div class="col-md">
            <validate tag="div">
              <label for="user_id">Username</label>
              <v-select name="user_id" v-model="model.user" :options="user" placeholder="Username" required></v-select>

              <field-messages name="user_id" show="$invalid && $submitted" class="text-danger">
                <small class="form-text text-success">Looks good!</small>
                <small class="form-text text-danger" slot="required">User is a required field</small>
              </field-messages>
            </validate>
          </div>
        </div>

        <div class="form-row mt-4">
          <div class="col-md">
            <button type="submit" class="btn btn-primary">Update</button>
            <button type="reset" class="btn btn-secondary" @click="reset">Reset</button>
          </div>
        </div>

      </vue-form>
    </div>
  </div>
</template>

<script>
import swal from 'sweetalert2';

export default {
  data() {
    return {
      state: {},
      title: 'Edit Akademik',
      model: {
        nomor_un          : '',
        bahasa_indonesia  : '',
        bahasa_inggris    : '',
        matematika        : '',
        ipa               : '',
        user_id           : '',
        created_at        : '',
        updated_at        : '',

        siswa             : '',
        user              : '',
      },
      siswa   : [],
      user    : [],
    }
  },
  mounted(){
    let app = this;

    axios.get('api/akademik/'+this.$route.params.id+'/edit')
      .then(response => {
        if (response.data.status == true && response.data.error == false) {
          this.model.nomor_un         = response.data.akademik.nomor_un;
          this.model.bahasa_indonesia = response.data.akademik.bahasa_indonesia;
          this.model.bahasa_inggris   = response.data.akademik.bahasa_inggris;
          this.model.matematika       = response.data.akademik.matematika;
          this.model.ipa              = response.data.akademik.ipa;
          this.model.user_id          = response.data.akademik.user_id;

          this.model.siswa            = response.data.akademik.siswa;

          if (response.data.akademik.user === null) {
            this.model.user = response.data.current_user;
          } else {
            this.model.user = response.data.akademik.user;
          }

          if(response.data.user_special == true){
            this.user = response.data.users;
          }else{
            this.user.push(response.data.users);
          }
        } else {
          swal(
            'Failed',
            'Oops... '+response.data.message,
            'error'
          );

          app.back();
        }
      })
      .catch(function(response) {
        swal(
          'Not Found',
          'Oops... Your page is not found.',
          'error'
        );

        app.back();
      });

    axios.get('api/siswa/get')
      .then(response => {
        if (response.data.status == true && response.data.error == false) {
          response.data.siswas.forEach(element => {
            this.siswa.push(element);
          });
        } else {
          swal(
            'Failed',
            'Oops... '+response.data.message,
            'error'
          );

          app.back();
        }
      })
      .catch(function(response) {
        swal(
          'Not Found',
          'Oops... Your page is not found.',
          'error'
        );

        app.back();
      });
  },
  methods: {
    onSubmit: function() {
      let app = this;

      if (this.state.$invalid) {
        return;
      } else {
        axios.put('api/akademik/'+this.$route.params.id, {
            nomor_un          : this.model.siswa.nomor_un ,
            bahasa_indonesia  : this.model.bahasa_indonesia,
            bahasa_inggris    : this.model.bahasa_inggris,
            matematika        : this.model.matematika,
            ipa               : this.model.ipa,
            user_id           : this.model.user.id,
          })
          .then(response => {
            if (response.data.status == true) {
              if(response.data.error == false){
                swal(
                  'Updated',
                  'Yeah!!! Your data has been updated.',
                  'success'
                );

                app.back();
              }else{
                swal(
                  'Failed',
                  'Oops... '+response.data.message,
                  'error'
                );
              }
            } else {
              swal(
                'Failed',
                'Oops... '+response.data.message,
                'error'
              );

              app.back();
            }
          })
          .catch(function(response) {
            swal(
              'Not Found',
              'Oops... Your page is not found.',
              'error'
            );

            app.back();
          });
      }
    },
    reset() {
      this.model = {
        nomor_un          : '',
        bahasa_indonesia  : '',
        bahasa_inggris    : '',
        matematika        : '',
        ipa               : '',
        user_id           : '',
        created_at        : '',
        updated_at        : '',

        siswa             : '',
        user              : '',
      };
    },
    view() {
      window.location = '#/admin/akademik/'+this.$route.params.id;
    },
    back() {
      window.location = '#/admin/akademik';
    }
  }
}
</script>
