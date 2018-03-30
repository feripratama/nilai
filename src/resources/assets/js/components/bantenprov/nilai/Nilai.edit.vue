<template>
  <div class="card">
    <div class="card-header">
      <i class="fa fa-table" aria-hidden="true"></i> Edit Nilai

      <ul class="nav nav-pills card-header-pills pull-right">
        <li class="nav-item">
          <button class="btn btn-primary btn-sm" role="button" @click="back">
            <i class="fa fa-arrow-left" aria-hidden="true"></i>
          </button>
        </li>
      </ul>
    </div>

    <div class="card-body">
      <vue-form class="form-horizontal form-validation" :state="state" @submit.prevent="onSubmit">

        <validate tag="div">
          <div class="form-group">
            <label for="model-nomor_un">Nomor UN</label>
            <input type="text" class="form-control" id="model-nomor_un" v-model="model.nomor_un" name="nomor_un" placeholder="Nomor UN" required>
            <field-messages name="nomor_un" show="$invalid && $submitted" class="text-danger">
              <small class="form-text text-success">Looks good!</small>
              <small class="form-text text-danger" slot="required">Nomor UN is a required field</small>
            </field-messages>
          </div>
        </validate>

        <div class="form-row mt-4">
          <div class="col-md">
            <validate tag="div">
            <label for="siswa_id">Siswa</label>
            <v-select name="siswa_id" v-model="model.siswa" :options="siswa" class="mb-4"></v-select>

            <field-messages name="siswa_id" show="$invalid && $submitted" class="text-danger">
              <small class="form-text text-success">Looks good!</small>
              <small class="form-text text-danger" slot="required">Siswa is a required field</small>
            </field-messages>
            </validate>
          </div>
        </div>

        <validate tag="div">
          <div class="form-group">
            <label for="model-akademik_id">Akademik</label>
            <input type="text" class="form-control" id="model-akademik_id" v-model="model.akademik_id" name="akademik_id" placeholder="Akademik" required>
            <field-messages name="akademik_id" show="$invalid && $submitted" class="text-danger">
              <small class="form-text text-success">Looks good!</small>
              <small class="form-text text-danger" slot="required">Akademik is a required field</small>
            </field-messages>
          </div>
        </validate>

        <div class="form-row mt-4">
          <div class="col-md">
            <validate tag="div">
            <label for="prestasi_id">Prestasi</label>
            <v-select name="prestasi_id" v-model="model.prestasi" :options="prestasi" class="mb-4"></v-select>

            <field-messages name="prestasi_id" show="$invalid && $submitted" class="text-danger">
              <small class="form-text text-success">Looks good!</small>
              <small class="form-text text-danger" slot="required">Prestasi is a required field</small>
            </field-messages>
            </validate>
          </div>
        </div>

        <validate tag="div">
          <div class="form-group">
            <label for="model-zona_id">Zona</label>
            <input type="text" class="form-control" id="model-zona_id" v-model="model.zona_id" name="zona_id" placeholder="Zona" required>
            <field-messages name="zona_id" show="$invalid && $submitted" class="text-danger">
              <small class="form-text text-success">Looks good!</small>
              <small class="form-text text-danger" slot="required">Zona is a required field</small>
            </field-messages>
          </div>
        </validate>

        <div class="form-row mt-4">
          <div class="col-md">
            <validate tag="div">
            <label for="sktm_id">SKTM</label>
            <v-select name="sktm_id" v-model="model.sktm" :options="sktm" class="mb-4"></v-select>

            <field-messages name="sktm_id" show="$invalid && $submitted" class="text-danger">
              <small class="form-text text-success">Looks good!</small>
              <small class="form-text text-danger" slot="required">SKTM is a required field</small>
            </field-messages>
            </validate>
          </div>
        </div>

         <div class="form-row mt-4">
          <div class="col-md">
            <validate tag="div">
            <label for="user_id">Username</label>
            <v-select name="user_id" v-model="model.user" :options="user" class="mb-4"></v-select>

            <field-messages name="user_id" show="$invalid && $submitted" class="text-danger">
              <small class="form-text text-success">Looks good!</small>
              <small class="form-text text-danger" slot="required">Username is a required field</small>
            </field-messages>
            </validate>
          </div>
        </div>

        <div class="form-row mt-4">
          <div class="col-md">            
            <button type="submit" class="btn btn-primary">Submit</button>

            <button type="reset" class="btn btn-secondary" @click="reset">Reset</button>            
          </div>
        </div>
        
      </vue-form>
    </div>
  </div>
</template>

<script>
export default {
  mounted() {
    axios.get('api/nilai/' + this.$route.params.id + '/edit')
      .then(response => {
        if (response.data.status == true) {
          this.model.user = response.data.user;
          this.model.nomor_un  = response.data.nilai.nomor_un;
          this.model.akademik_id  = response.data.nilai.akademik_id;
          this.model.prestasi  = response.data.prestasi;
          this.model.zona_id  = response.data.nilai.zona_id;
          this.model.sktm  = response.data.sktm;
          this.model.siswa = response.data.siswa;
        } else {
          alert('Failed');
        }
      })
      .catch(function(response) {
        alert('Break');
        window.location.href = '#/admin/nilai';
      }),

      axios.get('api/nilai/create')
      .then(response => {           
          response.data.siswa.forEach(element => {
            this.siswa.push(element);
          });
          response.data.user.forEach(element => {
            this.user.push(element);
          });
          response.data.prestasi.forEach(element => {
            this.prestasi.push(element);
          });
          response.data.sktm.forEach(element => {
            this.sktm.push(element);
          });
      })
      .catch(function(response) {
        alert('Break');
      })
  },
  data() {
    return {
      state: {},
      model: {
        nomor_un: "",
        siswa: "",
        akademik_id: "",
        prestasi: "",
        zona_id: "",
        sktm: "",
        user: ""
      },
      siswa: [],
      user: [],
      prestasi: [],
      sktm: []
    }
  },
  methods: {
    onSubmit: function() {
      let app = this;

      if (this.state.$invalid) {
        return;
      } else {
        axios.put('api/nilai/' + this.$route.params.id, {
            nomor_un: this.model.nomor_un,
            akademik_id: this.model.akademik_id,
            zona_id: this.model.zona_id,
            siswa_id: this.model.siswa.id,
            user_id: this.model.user.id,
            prestasi_id: this.model.prestasi.id,
            sktm_id: this.model.sktm.id
          })
          .then(response => {
            if (response.data.status == true) {
              if(response.data.message == 'success'){
                alert(response.data.message);
                app.back();
              }else{
                alert(response.data.message);
              }
            } else {
              alert(response.data.message);
            }
          })
          .catch(function(response) {
            alert('Break ' + response.data.message);
          });
      }
    },
    reset() {
      axios.get('api/nilai/' + this.$route.params.id + '/edit')
        .then(response => {
          if (response.data.status == true) {
            this.model.label = response.data.nilai.label;
            this.model.description = response.data.nilai.description;
          } else {
            alert('Failed');
          }
        })
        .catch(function(response) {
          alert('Break ');
        });
    },
    back() {
      window.location = '#/admin/nilai';
    }
  }
}
</script>
