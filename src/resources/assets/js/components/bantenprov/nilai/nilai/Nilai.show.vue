<template>
  <div class="card">
    <div class="card-header">
      <i class="fa fa-table" aria-hidden="true"></i> {{ title }}

      <div class="btn-group pull-right" role="group" style="display:flex;">
        <button class="btn btn-warning btn-sm" role="button" @click="edit">
          <i class="fa fa-pencil" aria-hidden="true"></i>
        </button>
        <button class="btn btn-primary btn-sm" role="button" @click="back">
          <i class="fa fa-arrow-left" aria-hidden="true"></i>
        </button>
      </div>
    </div>

    <div class="card-body">
      <dl class="row">
          <dt class="col-4">Nomor UN</dt>
          <dd class="col-8">{{ model.nomor_un }}</dd>

          <dt class="col-4">Nama Siswa</dt>
          <dd class="col-8">{{ model.siswa.nama_siswa }}</dd>

          <dt class="col-4">B. Indonesia</dt>
          <dd class="col-8">{{ model.bobot }}</dd>

          <dt class="col-4">B. Inggris</dt>
          <dd class="col-8">{{ model.akademik }}</dd>

          <dt class="col-4">Matematika</dt>
          <dd class="col-8">{{ model.prestasi }}</dd>

          <dt class="col-4">Zona</dt>
          <dd class="col-8">{{ model.zona }}</dd>

          <dt class="col-4">SKTM</dt>
          <dd class="col-8">{{ model.sktm }}</dd>
      </dl>
    </div>

    <div class="card-footer text-muted">
      <div class="row">
        <div class="col-md">
          <b>Username :</b> {{ model.user.name }}
        </div>
        <div class="col-md">
          <div class="col-md text-right">Dibuat : {{ model.created_at }}</div>
          <div class="col-md text-right">Diperbarui : {{ model.updated_at }}</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import swal from 'sweetalert2';

export default {
  data() {
    return {
      state: {},
      title: 'View Nilai',
      model: {
        nomor_un    : '',
        bobot       : '',
        akademik    : '',
        prestasi    : '',
        zona        : '',
        sktm        : '',
        user_id     : '',
        created_at  : '',
        updated_at  : '',

        siswa       : [],
        user        : [],
      },
    }
  },
  mounted() {
    let app = this;

    axios.get('api/nilai/'+this.$route.params.id)
      .then(response => {
        if (response.data.status == true && response.data.error == false) {
          this.model.nomor_un   = response.data.nilai.nomor_un;
          this.model.bobot      = response.data.nilai.bobot;
          this.model.akademik   = response.data.nilai.akademik;
          this.model.prestasi   = response.data.nilai.prestasi;
          this.model.zona       = response.data.nilai.zona;
          this.model.sktm       = response.data.nilai.sktm;
          this.model.user_id    = response.data.nilai.user_id;
          this.model.created_at = response.data.nilai.created_at;
          this.model.updated_at = response.data.nilai.updated_at;

          this.model.siswa      = response.data.nilai.siswa;
          this.model.user       = response.data.nilai.user;

          if (this.model.siswa === null) {
            this.model.siswa = {'id':this.model.nomor_un, 'nama_siswa':''};
          }

          if (this.model.user === null) {
            this.model.user = {'id':this.model.user_id, 'name':''};
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
  },
  methods: {
    edit() {
      window.location = '#/admin/nilai/'+this.$route.params.id+'/edit';
    },
    back() {
      window.location = '#/admin/nilai';
    }
  }
}
</script>