<template>
    <div>
        <div id="app" style="width: 100%;">
            <div id="content">
                <div class="row p-3">
                    <div class="col-md-3">
                        <date-picker name="date" v-model="date" :config="options"></date-picker>
                    </div>
                    <div class="d-flex col-md-9 justify-content-between">
                        <button @click="filter" class="btn btn-primary ml-2">Filter</button>
                        <button @click="loadCreateModal" class="btn btn-primary">Add New vehicle</button>
                    </div>

                </div>
                <table class="table">
                    <thead>
                    <tr>
                        <th>vieta</th>
                        <th v-for="col in columns">{{col}}</th>
                    </tr>
                    </thead>
                </table>
                <div v-if="loading">
                    <img class="rounded mx-auto d-block" :src="image" alt="loader">
                </div>
                <grid-layout
                    :layout=layout
                    :col-num=13
                    :row-height=40
                    :is-draggable=true
                    :is-resizable=false
                    :vertical-compact=true
                    :minH=40
                    :maxRows=13
                >
                    <grid-item v-for="item in layout" :key="item.id"
                               :numberPlate=item.numPlate
                               :x=item.x
                               :y=item.y
                               :w=0.8
                               :h=1
                               :i=item.numPlate
                    >
                        <span @click="loadUpdateModal(item.numPlate)" class="text">{{item.numPlate}}</span>
                    </grid-item>
                </grid-layout>
            </div>
        </div>

        <div>
            <div class="modal fade" id="createWindow" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Create New Vehicle</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <div class="alert alert-danger" v-if="errors.length > 0">
                                <ul>
                                    <li v-for="error in errors">{{error}}</li>
                                </ul>
                            </div>

                            <div class="form-group">
                                <label for="numberPlate">Number plate</label>
                                <input v-model="vehicle.numberPlate" id="numberPlate" type="text" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="columnId">Column ID</label>
                                <input v-model="vehicle.columnId" id="columnId" type="text" class="form-control">
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button @click="createVehicle" type="button" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="updateWindow" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editWindow">Update Vehicle</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-danger" v-if="errors.length > 0">
                                <ul>
                                    <li v-for="error in errors">{{error}}</li>
                                </ul>
                            </div>

                            <div class="form-group">
                                <label for="name">Number Plate</label>
                                <input v-model=vehicleUpdated.numPlate type="text" class="form-control" id="name">
                            </div>

                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <p class="font-weight-bold">s_datums: </p>
                                    <span class="badge badge-primary badge-pill">{{vehicleUpdated.s_datums || "null" }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <p class="font-weight-bold">Gate Nr: </p>
                                    <span class="badge badge-primary badge-pill">{{vehicleUpdated.x}}</span>
                                </li>
                            </ul>

                        </div>
                        <div class="modal-footer">
                            <div v-if="!vehicleUpdated.s_datums">
                                <button @click="deleteVehicle" class="btn btn-danger">Delete</button>
                            </div>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button @click="updateVehicle" type="button" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button @click="readJson()" class="btn btn-danger ">Read json</button>
    </div>
</template>

<script>
    import VueGridLayout from 'vue-grid-layout';
    import vehicleData from './assets/db.json';

    import 'bootstrap/dist/css/bootstrap.css';
    import datePicker from 'vue-bootstrap-datetimepicker';
    import 'pc-bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.css';

    let GridLayout = VueGridLayout.GridLayout;
    let GridItem = VueGridLayout.GridItem;

    export default {
        data() {
            return {
                date: new Date(),
                options: {
                    format: "YYYY-MM-DD HH:mm:ss",
                    useCurrent: true,
                },
                filterDateTime: "",
                vehicleData: vehicleData,
                columns: 12,
                rows: 14,
                vehicle: {
                    id: '',
                    numberPlate: '',
                    columnId: ''
                },
                vehicles: [],
                uri: '/vehicles/',
                errors: [],
                vehicleUpdated: [],
                image: 'images/loader1.gif',
                loading: true,
                toastr: toastr.options = {"positionClass": "toast-top-full-width"},
                layout: [],
                rowCounter: [],
            }
        },
        components: {
            GridLayout,
            GridItem,
            datePicker,
        },

        methods: {
            filter() {
                axios.post(this.uri + 'filter', {datetime: this.date})
                    .then(response => {
                        this.resetData();
                        let vehicles = response.data.vehicles;
                        vehicles.forEach(function (obj) {
                            this.add(obj.id, obj.column_id, obj.number_plate, obj.s_datums);
                        }, this)
                        toastr.success(response.data.message);
                    })
                    .catch(error => {
                        console.log(error.message);
                    });
            },

            readJson() {
                axios.post(this.uri + 'populateDb', {data: this.vehicleData.items})
                    .then(response => {
                        toastr.success(response.data.message);
                    })
                    .catch(error => {
                        this.errors = [];
                        if (error.response.data.errors.numberPlate) {
                            this.errors.push(error.response.data.errors.numberPlate[0]);
                        }
                        if (error.response.data.errors.columnId) {
                            this.errors.push(error.response.data.errors.columnId[0]);
                        }
                    });
            },

            add(index, columnId, numPlate, s_datums) {
                let self = this;
                if (this.rowCounter[columnId] < this.rows) {
                    let item = {
                        "index": parseInt(columnId),
                        "tableId": parseInt(index),
                        "numPlate": numPlate,
                        "x": parseInt(columnId),
                        "y": 0,
                        "w": 1,
                        "h": 1,
                        "i": numPlate,
                        "s_datums": s_datums
                    };
                    this.layout.push(item);
                    this.rowCounter[columnId]++;
                }
            },

            loadCreateModal() {
                $("#createWindow").modal("show");
            },

            loadUpdateModal(numPlate) {
                this.errors = [];
                $("#updateWindow").modal("show");
                this.vehicleUpdated = this.layout.find(obj => {
                    return obj.numPlate === numPlate
                })
            },

            createVehicle() {
                axios.post(this.uri, {numberPlate: this.vehicle.numberPlate, columnId: this.vehicle.columnId})
                    .then(response => {
                        let newVehicle = response.data.vehicle;
                        this.add(newVehicle.id, newVehicle.column_id, newVehicle.number_plate);
                        $("#createWindow").modal("hide");
                        toastr.success(response.data.message);
                    })
                    .catch(error => {
                        this.errors = [];
                        if (error.response.data.errors.numberPlate) {
                            this.errors.push(error.response.data.errors.numberPlate[0]);
                        }
                        if (error.response.data.errors.columnId) {
                            this.errors.push(error.response.data.errors.columnId[0]);
                        }
                    });
            },

            updateVehicle() {
                axios.patch(this.uri + this.vehicleUpdated.tableId, {
                    numberPlate: this.vehicleUpdated.numPlate,
                    columnId: this.vehicleUpdated.x,
                }).then(response => {
                    $("#updateWindow").modal("hide");
                    toastr.success(response.data.message);
                }).catch(error => {
                    console.log(error.message)
                    if (error.response.data.errors.numberPlate) {
                        this.errors.push(error.response.data.errors.numberPlate[0]);
                    }
                    if (error.response.data.errors.columnId) {
                        this.errors.push(error.response.data.errors.columnId[0]);
                    }
                });
            },

            deleteVehicle() {
                let confirmBox = confirm("Do you really want to delete this?");
                if (confirmBox == true) {
                    let id = this.vehicleUpdated.tableId;
                    axios.delete(this.uri + id)
                        .then(response => {
                            this.layout.splice(this.layout.indexOf(this.vehicleUpdated), 1);
                            $("#updateWindow").modal("hide");
                            toastr.info(response.data.message);
                        }).catch(error => {
                        console.log(error.message)
                    });
                }
            },

            loadVehicles() {
                this.resetData();
                axios.get(this.uri).then(response => {
                    let vehicles = response.data.vehicles;
                    vehicles.forEach(function (vehicle) {
                        this.add(vehicle.id, vehicle.column_id, vehicle.number_plate);
                    }, this)
                });
                this.loading = false;
            },

            resetData() {
                this.vehicle.numberPlate = '';
                this.vehicle.columnId = '';
                this.vehicle.id = '';
                this.layout = [];
                this.rowCounter = [];
                for (let i = 0; i < this.rows; i++) {
                    this.rowCounter[i] = 0;
                }
            },
        },

        mounted() {
            this.loadVehicles();
            console.log(this.layout)
        }
    }
</script>
