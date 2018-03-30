<template>
  <div class="card">
    <div class="card-header">
      <i class="fa fa-table" aria-hidden="true"></i> Nilai

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

        <div class="form-row">
          <div class="col-md">
            <b>Nomor UN :</b> {{ model.siswa.nomor_un }}
          </div>
        </div>

        <div class="form-row mt-4">
          <div class="col-md">
            <b>Siswa :</b> {{ model.siswa.nama_siswa }}
          </div>
        </div>

        <div class="form-row mt-4">
          <div class="col-md">
            <b>Akademik :</b> {{ model.akademik_id }}
          </div>
        </div>

        <div class="form-row mt-4">
          <div class="col-md">
            <b>Prestasi :</b> {{ model.prestasi.nama_lomba }}
          </div>
        </div>

        <div class="form-row mt-4">
          <div class="col-md">
            <b>Zona :</b> {{ model.zona_id }}
          </div>
        </div>

        <div class="form-row mt-4">
          <div class="col-md">
            <b>STKM :</b> {{ model.sktm.no_sktm }}
          </div>
        </div>

      </vue-form>
    </div>
       <div class="card-footer text-muted">
        <div class="row">
          <div class="col-md">
            <b>Username :</b> {{ model.user.name }}
          </div>
          <div class="col-md">
            <div class="col-md text-right">Dibuat : {{ model.created_at }}</div>
            <div class="col-md text-right">Diperbaiki : {{ model.updated_at }}</div>
          </div>
        </div>
      </div>
  </div>
</template>

<script>
export default {
  mounted() {
    axios.get('api/nilai/' + this.$route.params.id)
      .then(response => {
        if (response.data.status == true) {
          this.model.nomor_un = response.data.nilai.nomor_un;
          this.model.siswa = response.data.siswa;
          this.model.akademik_id = response.data.nilai.akademik_id;
          this.model.prestasi = response.data.prestasi;
          this.model.zona_id = response.data.nilai.zona_id;
          this.model.sktm = response.data.sktm;
          this.model.user = response.data.nilai.user;          
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
            label: this.model.label,
            description: this.model.description,
            old_label: this.model.old_label,
            siswa_id: this.model.siswa.id
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
