<template>
  <div class="container">
    <div class="row mx-0 justify-content-center">
      <div class="col-8">
        <h1 class="d-flex align-items-end mt-5 py-3">
          Direction list
          <button class="btn ml-auto text-primary" @click="create=true" style="font-weight: 600">
            <i class="fas fa-plus"></i> Create
          </button>
        </h1>
      </div>
    </div>
    <transition name="slide-fade">
      <form class="row mx-0 my-4 justify-content-center" @submit.prevent="store" v-if="create">
        <div class="col-md-8 py-2 d-flex bg-white rounded">
          <input type="text" class="text-left form-control" v-model="direction.name" placeholder="Direction name">
          <button class="btn text-primary">
            Create
          </button>
          <button type="button" class="btn text-danger" @click="create=false">
            Cancel
          </button>
        </div>
      </form>
    </transition>
    <transition-group name="slide-fade" tag="div">
    <div class="row mx-0 mb-2 justify-content-center" v-for="dir in directions" :key="dir.id">
      <div class="col-md-8 py-2 d-flex bg-white">
        <div class="holder" v-if="!dir.edit">{{ dir.name }}</div>
        <input type="text" class="input" v-model="dir.name" v-if="dir.edit">
        <button class="btn text-primary" v-if="!dir.edit" @click="dir.edit=true">
          Edit
        </button>
        <button class="btn text-primary" v-if="dir.edit" @click="update(dir)">
          Update
        </button>
        <button class="btn text-danger" v-if="dir.edit" @click="dir.edit=false">
          Cancel
        </button>
        <button type="type" class="btn text-danger" v-if="!dir.edit" @click="showDestroy(dir)">
          Delete
        </button>
      </div>
    </div>
    </transition-group>
    <modal name="destroy-form" :width="300" :class="['text-center']">
      <h3 class="px-3 py-4 text-danger">Delete confirm</h3>
      <div>Are you sure?</div>
      <div class="form-group pt-4">
        <button class="btn text-primary" @click="hideDestroy">Cancel</button>
        <button class="btn text-muted" @click="destroy">Confirm</button>
      </div>
    </modal>
  </div>
</template>
<script>
import { $request } from '../../../auth'

export default {
  name: 'direction-list',
  data () {
    return {
      chosen: '',
      create: false,
      edit: false,
      directions: [],
      direction: {
        name: ''
      }
    }
  },
  mounted () {
    this.list()
  },
  methods: {
    showDestroy (dir) {
      this.chosen = dir
      this.$modal.show('destroy-form')
    },
    hideDestroy () {
      this.$modal.hide('destroy-form')
    },
    choose (dir) {
      this.chosen = {
        id: dir.id,
        name: dir.name
      }
    },
    list () {
      ajax().get('/api/direction')
      .then(res => {
        res.data.forEach(dir => {
          dir.edit = false
          this.directions.push(dir)
        });
      })
      .catch(err => {
        console.log(err.response.data.message)
        this.error('Unable to get direction list')
      })
    },
    store () {
      ajax().post('/api/direction', this.direction)
      .then(res => {
        this.direction.name = ''
        res.data.edit = false
        this.directions.push(res.data)
        this.success('New direction has been created')
      })
      .catch(err => {
        console.log(err.response.data.message)
        this.error('Unable to create new direction')
      })
    },
    update (dir) {
      ajax().put(`/api/direction/${dir.id}`, dir)
      .then(res => {
        dir.edit = false
        this.success('Record has been updated')
      })
      .then(err => {
        dir.name = this.chosen.name
        console.log(err.response.data.message)
        this.error('Unable to update this record')
      })
    },
    destroy () {
      ajax().delete(`/api/direction/${this.chosen.id}`)
      .then(res => {
        this.directions = this.directions.filter(dir => dir.id!=this.chosen.id)
        this.success('Record has been deleted')
        this.hideDestroy()
      })
      .catch(err => {
        console.log(err.response.data.message)
        this.error('Unable to delete this record')
      })
    },
    success (message) {
      $eventHub.$emit('success-alert', {
        title: 'Success',
        message: message,
        timeout: 3000
      })
    },
    error (message) {
      $eventHub.$emit('error-alert', {
        title: 'Error',
        message: message
      })
    }
  }
}
</script>
