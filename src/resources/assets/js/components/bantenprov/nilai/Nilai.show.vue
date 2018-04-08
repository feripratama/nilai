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
            <b>Nama Siswa :</b> {{ model.siswa.nama_siswa }}
          </div>
        </div>

         <div class="form-row mt-4">
          <div class="col-md">
            <b>Akademik :</b> {{ model.akademik }}
          </div>
        </div>

        <div class="form-row mt-4">
          <div class="col-md">
            <b>Prestasi :</b> {{ model.prestasi }}
          </div>
        </div>

        <div class="form-row mt-4">
          <div class="col-md">
            <b>Zona :</b> {{ model.zona }}
          </div>
        </div>

        <div class="form-row mt-4">
          <div class="col-md">
            <b>Aktm :</b> {{ model.sktm }}
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
          this.model.siswa = response.data.siswa;
          this.model.user = response.data.user;
          this.model.akademik = response.data.nilai.akademik;
          this.model.prestasi = response.data.nilai.prestasi;
          this.model.zona = response.data.nilai.zona;
          this.model.sktm = response.data.nilai.sktm;
          this.model.created_at = response.data.nilai.created_at;
          this.model.updated_at = response.data.nilai.updated_at;
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
          if(response.data.user_special == true){
            response.data.user.forEach(user_element => {
              this.user.push(user_element);
            });
          }else{
            this.user.push(response.data.user);
          }
      })
      .catch(function(response) {
        alert('Break');
        window.location.href = '#/admin/nilai';
      })
  },
  data() {
    return {
      state: {},
      model: {
        siswa: "",
        user: "",
        akademik: "",
        prestasi: "",
        zona: "",
        sktm: "",
        created_at: "",
        updated_at: ""
      },
      siswa: [],
      user: []
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
