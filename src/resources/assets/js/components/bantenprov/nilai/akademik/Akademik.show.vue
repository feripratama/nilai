<template>
  <div class="card">
    <div class="card-header">
      <i class="fa fa-table" aria-hidden="true"></i> Akademik

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
            <b>Bahasa Indonesia :</b> {{ model.bahasa_indonesia }}
          </div>
        </div>

        <div class="form-row mt-4">
          <div class="col-md">
            <b>Bahasa Inggris :</b> {{ model.bahasa_inggris }}
          </div>
        </div>

        <div class="form-row mt-4">
          <div class="col-md">
            <b>Matematika :</b> {{ model.matematika }}
          </div>
        </div>

        <div class="form-row mt-4">
          <div class="col-md">
            <b>IPA :</b> {{ model.ipa }}
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
    axios.get('api/akademik/' + this.$route.params.id)
      .then(response => {
        if (response.data.status == true) {
          this.model.siswa = response.data.siswa;
          this.model.bahasa_indonesia = response.data.akademik.bahasa_indonesia;
          this.model.bahasa_inggris = response.data.akademik.bahasa_inggris;
          this.model.matematika = response.data.akademik.matematika;
          this.model.ipa = response.data.akademik.ipa;
          this.model.user = response.data.user;
          this.model.created_at = response.data.akademik.created_at;
          this.model.updated_at = response.data.akademik.updated_at;
        } else {
          alert('Failed');
        }
      })
      .catch(function(response) {
        alert('Break');
        window.location.href = '#/admin/akademik';
      }),

      axios.get('api/akademik/create')
      .then(response => {
          if(response.data.user_special == true){
            response.data.user.forEach(user_element => {
              this.user.push(user_element);
            });
          }else{
            this.user.push(response.data.user);
          }
          response.data.siswa.forEach(element => {
            this.siswa.push(element);
          });
      })
      .catch(function(response) {
        alert('Break');
        window.location.href = '#/admin/akademik';
      })
  },
  data() {
    return {
      state: {},
      model: {
        siswa: "",
        bahasa_indonesia: "",
        bahasa_inggris: "",
        matematika: "",
        ipa: "",
        user: ""
      },
      user: [],
      siswa: []
    }
  },
  methods: {
    onSubmit: function() {
      let app = this;

      if (this.state.$invalid) {
        return;
      } else {
        axios.put('api/akademik/' + this.$route.params.id, {
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
      axios.get('api/akademik/' + this.$route.params.id + '/edit')
        .then(response => {
          if (response.data.status == true) {
            this.model.label = response.data.akademik.label;
            this.model.description = response.data.akademik.description;
          } else {
            alert('Failed');
          }
        })
        .catch(function(response) {
          alert('Break ');
        });
    },
    back() {
      window.location = '#/admin/akademik';
    }
  }
}
</script>
