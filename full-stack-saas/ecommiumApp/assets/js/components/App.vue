<template>
  <div class="container">
    <div style="margin-top: 25px">
      <button class="btn btn-info" @click="showCreate">Create process</button>
      <button class="btn btn-info" @click="showProcesses">List processes</button>
    </div>
    <div v-if="showListProcesses">
      <div style="margin-top: 50px;">
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
              <button class="btn btn-success" v-if="p.status === 0" @click="">Start</button>
            </td>
          </tr>
          </tbody>
        </table>
        <p class="text-center font-weight-bold" style="color: red" v-if="processes.length === 0">Not Results</p>
      </div>
    </div>
    <div v-if="showCreateProcess">
    </div>
  </div>
</template>

<script>
import axios from 'axios';

var BASE_URL = window.location.href;
var urlGetAll = BASE_URL + 'data';

export default {
  data() {
    return {
      processes: [],
      showListProcesses: true,
      showCreateProcess: false
    }
  },
  mounted() {
    this.allProcesses();
  },
  methods: {
    allProcesses: function () {
      axios.get(urlGetAll)
          .then((response) => {
            this.processes = response.data.processes;
          })
    },
    showProcesses: function () {
      this.showListProcesses = true;
      this.showCreateProcess = false;
    },
    showCreate: function () {
      this.showCreateProcess = true;
      this.showListProcesses = false;
    }
  }
}
</script>