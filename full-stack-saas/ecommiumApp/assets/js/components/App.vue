<template>
  <div class="container">
    <div style="margin-top: 25px">
      <button class="btn btn-info" @click="showCreate">Create process</button>
      <button class="btn btn-info" @click="showProcesses">List processes</button>
    </div>
    <div style="margin-top: 50px;">
      <!--LIST PROCESSES-->
      <div v-if="showListProcesses">
        <table class="table table-striped table-bordered">
          <thead>
          <tr>
            <th>Id</th>
            <th>Input</th>
            <th>Output</th>
            <th>Created at</th>
            <th>Started at</th>
            <th>Finished at</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
          </thead>
          <tbody v-for="p in processes">
          <tr>
            <td>{{ p.id }}</td>
            <td>{{ p.input }}</td>
            <td>{{ p.output }}</td>
            <td>{{ p.createdAt }}</td>
            <td>{{ p.startedAt }}</td>
            <td>{{ p.finishedAt }}</td>
            <td v-if="p.status === 0">NOT STARTED</td>
            <td v-if="p.status === 1">PROCESSING</td>
            <td v-if="p.status === 2">FINISHED</td>
            <td>
              <button class="btn btn-success" v-if="p.status === 0" @click="runProcess(p.id)">Start</button>
            </td>
          </tr>
          </tbody>
        </table>
        <p v-if="(processes.length === 0) && (!loading)" class="text-center font-weight-bold" style="color: red">Not
          Results</p>
        <p v-if="loading" class="text-center">Loading...</p>
      </div>
      <!--CREATE PROCESS-->
      <div v-if="showCreateProcess" class="m-0 row justify-content-center">
        <div class="col-auto">
          <h5>Create process</h5>
          <hr>
          <div class="form-group" style="display: inline-flex">
            <label>Type</label>
            <select v-model="typeSelected" class="form-control" style="margin-left: 10px">
              <option v-for="type in types" :value="type.id">{{ type.name }}</option>
            </select>
          </div>
          <div class="form-group">
            <label>Input</label>
            <textarea class="form-control" v-model="input" maxlength="100"></textarea>
          </div>
          <div>
            <button class="btn btn-success" @click="createProcess(0)">Create</button>
            <button class="btn btn-danger" @click="createProcess(1)">Create and Run</button>
          </div>
          <p style="margin-top: 20px;" :style="color">{{ msg }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

var BASE_URL = window.location.href;
var urlGetAll = BASE_URL + 'data';
var urlCreate = BASE_URL + 'create';
var urlUpdate = BASE_URL + 'run';

export default {
  data() {
    return {
      idProcess: null,
      processes: [],
      loading: false,
      showListProcesses: true,
      showCreateProcess: false,
      types: [
        {
          id: 0,
          name: 'NONE'
        },
        {
          id: 1,
          name: 'VOWELS_COUNT'
        }
      ],
      typeSelected: null,
      input: null,
      msg: null,
      color: null
    }
  },
  mounted() {
    this.allProcesses();
  },
  methods: {
    allProcesses: function () {
      this.loading = true;
      axios.get(urlGetAll)
          .then((response) => {
            this.processes = response.data.processes;
            this.loading = false;
          })
    },
    showProcesses: function () {
      this.showListProcesses = true;
      this.showCreateProcess = false;
    },
    showCreate: function () {
      this.showCreateProcess = true;
      this.showListProcesses = false;
    },
    createProcess: function (action) {
      this.msg = null;
      axios.post(urlCreate,
          {
            type: this.typeSelected,
            input: this.input,
            action: action
          })
          .then((response) => {
            this.msg = response.data.msg;
            this.color = response.data.error ? 'color:red' : 'color:green';
            if (!response.data.error) {
              this.allProcesses();
              if (action === '1') {
                this.idProcess = response.data.id;
                this.runProcess(this.idProcess);
              }
            }
          })
    },
    runProcess: function (idProcess) {
      axios.put(urlUpdate,
          {
            id: idProcess
          })
          .then((response) => {
            this.allProcesses();
          })
    }
  }
}
</script>