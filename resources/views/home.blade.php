@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <v-app id="inspire">
                        <v-stepper v-model="e1">
                            <v-stepper-header>
                                <v-stepper-step :complete="e1 > 1" step="1">Select a School</v-stepper-step>
                                <v-divider></v-divider>
                                <v-stepper-step :complete="e1 > 2" step="2">Select a Program</v-stepper-step>
                                <v-divider></v-divider>
                                <v-stepper-step :complete="e1 > 3" step="3">Select a Track</v-stepper-step>
                                <v-divider></v-divider>
                                <v-stepper-step :complete="e1 > 4" step="4">Options</v-stepper-step>
                                <v-divider></v-divider>
                                <v-stepper-step step="5">View/Edit</v-stepper-step>
                            </v-stepper-header>

                            <v-stepper-items>
                                <v-stepper-content step="1">

                                    <v-card class="mb-5">

                                        <div class="field">
                                          <label class="label">Select a school</label>
                                          <div class="control">
                                            <div class="select">
                                              <select v-model="activeSchool">
                                                <option disabled>---</option>
                                                <option v-for="(value, key, index) in schools" :value="value.id" v-text="value.name"></option>
                                              </select>
                                            </div>
                                          </div>
                                        </div>

                                        <v-btn color="info" @click.prevent="selectedSchool()">Next</v-btn>

                                    </v-card>

                                </v-stepper-content>
                                <v-stepper-content step="2">

                                    <v-card class="mb-5">
                                        
                                        <div class="field">
                                          <label class="label">Select a program</label>
                                          <div class="control">
                                            <div class="select">
                                              <select v-model="activeProgram">
                                                <option disabled>---</option>
                                                <option v-for="(value, key, index) in programs" :value="value.id" v-text="value.name"></option>
                                              </select>
                                            </div>
                                          </div>
                                        </div>

                                        <v-btn color="info" @click.prevent="selectedProgram()">Next</v-btn>
                                        
                                    </v-card>

                                </v-stepper-content>
                                <v-stepper-content step="3">

                                    <v-card class="mb-5">
                                        
                                        <div class="field">
                                          <label class="label">Select a track</label>
                                          <div class="control">
                                            <div class="select">
                                              <select v-model="activeTrack">
                                                <option disabled>---</option>
                                                <option v-for="(value, key, index) in tracks" :value="value.id" v-text="value.name"></option>
                                              </select>
                                            </div>
                                          </div>
                                        </div>

                                        <v-btn color="info" @click.prevent="selectedTrack()">Next</v-btn>
                                        
                                    </v-card>
                          
                                </v-stepper-content>
                                <v-stepper-content step="4">
                          
                                    <v-card class="mb-5">

                                        <div class="field">
                                          
                                            <label class="label">Credits Taken So Far</label>
                                          
                                          <div class="field-body">
                                            <div class="field">
                                              <div class="control">
                                                <input class="input" type="text" placeholder="" v-model="takenCredits" v-on:change="calculateRemainingCredits()" @mouseover="ctsf = 1" @mouseout="ctsf = 0">
                                              </div>
                                            </div>
                                          </div>

                                        </div>

                                        <div class="field">
                                          <label class="label">Forecasted Start Date</label>
                                          <div class="control">
                                            <div class="select">
                                              <select v-model="activeStartDate" v-on:change="selectedForecastedStartDate()">
                                                <option disabled>---</option>
                                                <option v-for="(value, key, index) in startDates" :value="value.start_date" v-text="value.name"></option>
                                              </select>
                                            </div>
                                          </div>
                                        </div>

                                        <div class="field">
                                          <label class="label">Track Speed</label>
                                          <div class="control">
                                            <div class="select">
                                              <select v-model="activeTrackSpeed" v-on:change="selectedTrackSpeed()" @mouseover="ts = 1" @mouseout="ts = 0">
                                                <option disabled>---</option>
                                                <option value="standard">Standard</option>
                                                <option value="accelerated">Accelerated</option>
                                              </select>
                                            </div>
                                          </div>
                                        </div>

                                        <v-btn color="info" @click.prevent="selectedOptions()">Next</v-btn>

                                    </v-card>

                                </v-stepper-content>
                                <v-stepper-content step="5">
                          
                                    <v-card class="mb-5">
                                        <div v-for="(value, key, index) in activeSchoolData"><b>School:</b> @{{ value.name }}</div>
                                        <div v-for="(value, key, index) in activeProgramData"><b>Program:</b> @{{ value.name }}</div>
                                        <div v-for="(value, key, index) in activeTrackData"><b>Track:</b> @{{ value.name }}</div>

                                        <div class="field">
                                          <label class="label">Credits Remaining</label>
                                          <div class="field-body">
                                            <div class="field">
                                              <div class="control">
                                                <input class="input" v-bind:disabled="lockEditing" type="text" placeholder="" v-model="remainingCredits" v-on:change="buildTransformedTrackArray(2)" @mouseover="cr = 1" @mouseout="cr = 0">
                                              </div>
                                            </div>
                                          </div>
                                        </div>

                                        <div class="field">
                                          <label class="label">Forecasted Start Date</label>
                                          <div class="control">
                                            <div class="select">
                                              <select v-model="activeStartDate" v-on:change="buildTransformedTrackArray(2)" v-bind:disabled="lockEditing">
                                                <option disabled>---</option>
                                                <option v-for="(value, key, index) in startDates" :value="value.start_date" v-text="value.name"></option>
                                              </select>
                                            </div>
                                          </div>

                                        <div class="field">
                                          <label class="label">Track Speed</label>
                                          <div class="control">
                                            <div class="select">
                                              <select v-model="activeTrackSpeed" v-on:change="buildTransformedTrackArray(2)" v-bind:disabled="lockEditing">
                                                <option disabled>---</option>
                                                <option value="standard">Standard</option>
                                                <option value="accelerated">Accelerated</option>
                                              </select>
                                            </div>
                                          </div>
                                        </div>

                                        <table style="width:100%">
                                          <tr>
                                            <th>Session Name</th>
                                            <th class="text-right">Start Date</th> 
                                            <th class="text-right">End Date</th>
                                            <th class="text-center">Credits Before</th>
                                            <th class="text-center">Credits After</th>
                                            <th>Credits Taking</th>
                                          </tr>
                                          <tr v-for="(value, key, index) in transformedTrack" v-if="value.credits_before > 0">
                                            <td v-text="value.name"></td>
                                            <td class="text-right">@{{ value.start_date | dateout }}</td>
                                            <td class="text-right">@{{ value.end_date | dateout }}</td>
                                            <td v-text="value.credits_before" class="text-center"></td>
                                            <td v-text="value.credits_after" class="text-center"></td>
                                            <td>
                                                <div class="field-body">
                                                    <div class="field">
                                                        <div class="control">
                                                            <input class="input" type="text" placeholder="" v-model="value.credits_taking" v-on:change="buildTransformedTrackArray(3, key)" @mouseover="ct = 1" @mouseout="ct = 0">
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                          </tr>
                                        </table>

                                        <v-btn color="info" @click.prevent="screenshot()">Capture Screenshot</v-btn>

                                    </v-card>

                                </v-stepper-content>
                            </v-stepper-items>
                        </v-stepper>
                    </v-app>



                </div>
            </div>
        </div>
    </div>
</div>
@endsection