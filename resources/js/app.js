
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import draggable from 'vuedraggable';
import Vuetify from 'vuetify';
import html2canvas from 'html2canvas';
import jsPDF from 'jspdf';

Vue.use(Vuetify);

Vue.component('tabs', {
    template: `
        <div>
            <div class="tabs is-toggle is-fullwidth is-medium">
                <ul>
                    <li v-for="tab in tabs" :class="{ 'is-active': tab.isActive }">
                        <a :href="tab.href" @click="selectTab(tab)">Step</a>
                    </li>
                </ul>
            </div>
            <div class="tabs-details">
                <slot></slot>
            </div>
        </div>
    `,

    data() {
        return { tabs: [] };
    },

    created() {
        this.tabs = this.$children;
    },

    methods: {
        selectTab(selectedTab) {
            this.tabs.forEach(tab => {
                tab.isActive = (tab.href == selectedTab.href);
            });
        }
    }
});

Vue.component('tab', {
    template: `
        <div v-show="isActive"><slot></slot></div>
    `,

    props: {
        name: { required: true },
        selected: { default: false }
    },

    data() {
        return {
            isActive: false
        };
    },

    computed: {
        href() {
            return '#' + this.name.toLowerCase().replace(/ /g, '-');
        }
    },

    mounted() {
        this.isActive = this.selected;
    },
});

Vue.filter('dateout', function (value) {
  if (!value) {return '';}
  value = new Date(value);
  let month = value.getMonth() + 1;
  let day = value.getDate();
  let year = value.getFullYear();
  return month + "-" + day + "-" + year;
});

new Vue({

	el: '#app',

	data: {
 
		e1: 0,
		schools: [],
		programs: [],
		tracks: [],
		activeSchool: "",
		activeProgram: "",
		activeTrack: "",
		activeSchoolData: [],
		activeProgramData: [],
		activeTrackData: [],
		activeStartDate: "",
		activeTrackSpeed: "",
		activeSummerSchoolChoice: "",
		transformedTrack: [],
		placeholderTrack: [],
		takenCredits: [],
		remainingCredits: 0,
		remainingCreditsArray: [],
		tempCredits: 0,
		flag: 0,
		lockEditing: false,
		startDates: [],
		pngUrl: "",
		dialog: false,
		ctsf: 0,
		ts: 0,
		ct: 0,
		cr: 0,
	},

	computed: {

    	remainingCreditsCalc() {

			 if (this.e1 == 5) {

			 	if (this.remainingCredits > 0 && this.flag == 1) {
			 		this.remainingCreditsArray.push(this.remainingCredits);
					this.remainingCredits = this.remainingCredits - 6;
					this.remainingCreditsArray.push(this.remainingCredits);
					this.flag = 0;
				} else if(this.remainingCredits > 0 && this.flag == 2) {
					this.remainingCreditsArray.push(this.remainingCredits);
					this.remainingCredits = this.remainingCredits - 10;
					this.remainingCreditsArray.push(this.remainingCredits);
					this.flag = 0;
				} else {
					this.remainingCreditsArray.push(this.remainingCredits);
					this.remainingCreditsArray.push(this.remainingCredits);
					this.flag = 0;
				}

			 }

		}

  	},

  	watch: {

	},

	components: {
		draggable
	},

  	mounted() {

    	axios.get('/api/schools').then( response => this.schools = response.data );

	},

	methods: {

		computedCredits(start,end) {

			this.flag = 0;
    		var sessionStartDate = new Date(start);
    		var sessionStartDate = sessionStartDate.getTime();
    		var sessionEndDate = new Date(end);
    		var sessionEndDate = sessionEndDate.getTime();
    		var startingDate = new Date(this.activeStartDate);
    		var startingDate = startingDate.getTime();

    		if (startingDate > sessionStartDate) {
    			return "Start date comes after this session";
    		} else if (startingDate <= sessionEndDate && startingDate >= sessionStartDate) {
    			this.flag = 1;
    			return "First Semester";
    		} else if (startingDate < sessionStartDate) {
    			this.flag = 2;
    			return "Normal Semester";
    		}


		},

		selectedSchool() {

			axios.get('/api/programs/' + this.activeSchool).then( response => this.programs = response.data );
			axios.get('/api/school/' + this.activeSchool).then( response => this.activeSchoolData = response.data );
			axios.get('/api/sessions/' + this.activeSchool).then( response => this.startDates = response.data );

        	this.e1 = "2";

		},

		selectedProgram() {

			axios.get('/api/tracks/' + this.activeProgram).then( response => this.tracks = response.data );
			axios.get('/api/program/' + this.activeProgram).then( response => this.activeProgramData = response.data );

			this.e1 = "3";

		},

		selectedTrack() {

			axios.get('/api/track/' + this.activeTrack).then( response => this.activeTrackData = response.data );
			
			this.e1 = "4";

		},

		selectedOptions() {

			this.e1 = "5";
			this.buildTransformedTrackArray(1);

		},

		calculateRemainingCredits() {

			this.remainingCredits = this.activeTrackData[0].credits - this.takenCredits;

		},


		// TODO - CLEAN THIS UP
		buildTransformedTrackArray(type, identity) {

			if(type == 3) {

				this.lockEditing = true;

				let tempSessionData = this.transformedTrack;
				
				this.transformedTrack.forEach(function(value, i) {

					if (i == 0) {

						value['credits_before'] = value['credits_before'];
						value['credits_after'] = value['credits_before'] - value['credits_taking'];
						tempSessionData[i]['credits_after'] = value['credits_after'];

					} else {

						value['credits_before'] = tempSessionData[i - 1]['credits_after'];
						value['credits_after'] = value['credits_before'] - value['credits_taking'];
						tempSessionData[i]['credits_after'] = value['credits_after'];

					}


				});

				return false;
			} else if(type == 2) {

				this.lockEditing = false;

				this.transformedTrack = [];
				let tempTrackSpeed = this.activeTrackSpeed;
				let tempStarDate = this.activeStartDate;
				let tempTrackArray = this.transformedTrack;
				let defaultMaxCreditsStandard = this.activeSchoolData[0].default_max_credits_standard;
				let firstSemesterMaxCredits = this.activeSchoolData[0].first_semester_max_credits;
				let firstSemesterMaxCreditsAccelerated = this.activeSchoolData[0].first_semester_max_credits_accelerated;
				let defaultCreditsStandard = this.activeSchoolData[0].default_credits_standard;
				let defaultCreditsAccelerated = this.activeSchoolData[0].default_credits_accelerated;
				let totalCredits = this.remainingCredits;

				this.startDates.forEach(function(value, i){

					let tempObject = {};
					let tempCredits = 0;

					tempObject['credits_before'] = totalCredits;

					for (var key in value) {

						if (key == 'name') {

							tempObject['name'] = value[key];

						}

						if (key == 'start_date') {

							tempObject['start_date'] = value[key];
							
						}

						if (key == 'end_date') {

							tempObject['end_date'] = value[key];
							
						}

					}

					// BEGIN CREDIT CALCULATIONS
					if (tempTrackSpeed == 'standard') {

						if (tempStarDate == value.start_date) {

							if (totalCredits - firstSemesterMaxCredits >= 0) {
								tempObject['credits_taking'] = firstSemesterMaxCredits;
							} else {
								let tempVariable = totalCredits - firstSemesterMaxCredits;
								tempVariable = Math.abs(tempVariable); 
								tempObject['credits_taking'] = firstSemesterMaxCredits - tempVariable;
							}
							
							totalCredits = totalCredits - tempObject['credits_taking'];

						} else if (tempStarDate < value.start_date) {

							if (totalCredits - defaultCreditsStandard >= 0) {
								tempObject['credits_taking'] = defaultCreditsStandard;
							} else {
								let tempVariable = totalCredits - defaultCreditsStandard;
								tempVariable = Math.abs(tempVariable); 
								tempObject['credits_taking'] = defaultCreditsStandard - tempVariable;
							}

							totalCredits = totalCredits - tempObject['credits_taking'];

						} else {
							return false;
						}

						

					} else if (tempTrackSpeed == 'accelerated') {

						if (tempStarDate == value.start_date) {

							if (totalCredits - firstSemesterMaxCreditsAccelerated >= 0) {
								tempObject['credits_taking'] = firstSemesterMaxCreditsAccelerated;
							} else {
								let tempVariable = totalCredits - firstSemesterMaxCreditsAccelerated;
								tempVariable = Math.abs(tempVariable); 
								tempObject['credits_taking'] = firstSemesterMaxCreditsAccelerated - tempVariable;
							}

							totalCredits = totalCredits - tempObject['credits_taking'];

						} else if (tempStarDate < value.start_date) {

							if (totalCredits - defaultCreditsAccelerated >= 0) {
								tempObject['credits_taking'] = defaultCreditsAccelerated;
							} else {
								let tempVariable = totalCredits - defaultCreditsAccelerated;
								tempVariable = Math.abs(tempVariable); 
								tempObject['credits_taking'] = defaultCreditsAccelerated - tempVariable;
							}

							totalCredits = totalCredits - tempObject['credits_taking'];

						} else {
							return false;
						}

					}
					// END CREDIT CALCULATIONS

					tempObject['credits_after'] = totalCredits;
					tempTrackArray.push(tempObject);
					

				});

				this.transformedTrack = tempTrackArray;

				return false;

			} else if (type == 1){

				this.lockEditing = false;

				this.transformedTrack = [];
				let tempTrackSpeed = this.activeTrackSpeed;
				let tempStarDate = this.activeStartDate;
				let tempTrackArray = this.transformedTrack;
				let defaultMaxCreditsStandard = this.activeSchoolData[0].default_max_credits_standard;
				let firstSemesterMaxCredits = this.activeSchoolData[0].first_semester_max_credits;
				let firstSemesterMaxCreditsAccelerated = this.activeSchoolData[0].first_semester_max_credits_accelerated;
				let defaultCreditsStandard = this.activeSchoolData[0].default_credits_standard;
				let defaultCreditsAccelerated = this.activeSchoolData[0].default_credits_accelerated;
				let totalCredits = this.remainingCredits;

				this.startDates.forEach(function(value, i){

					let tempObject = {};
					let tempCredits = 0;

					tempObject['credits_before'] = totalCredits;

					for (var key in value) {

						if (key == 'name') {

							tempObject['name'] = value[key];

						}

						if (key == 'start_date') {

							tempObject['start_date'] = value[key];
							
						}

						if (key == 'end_date') {

							tempObject['end_date'] = value[key];
							
						}

					}

					// BEGIN CREDIT CALCULATIONS
					if (tempTrackSpeed == 'standard') {

						if (tempStarDate == value.start_date) {

							if (totalCredits - firstSemesterMaxCredits >= 0) {
								tempObject['credits_taking'] = firstSemesterMaxCredits;
							} else {
								let tempVariable = totalCredits - firstSemesterMaxCredits;
								tempVariable = Math.abs(tempVariable); 
								tempObject['credits_taking'] = firstSemesterMaxCredits - tempVariable;
							}
							
							totalCredits = totalCredits - tempObject['credits_taking'];

						} else if (tempStarDate < value.start_date) {

							if (totalCredits - defaultCreditsStandard >= 0) {
								tempObject['credits_taking'] = defaultCreditsStandard;
							} else {
								let tempVariable = totalCredits - defaultCreditsStandard;
								tempVariable = Math.abs(tempVariable); 
								tempObject['credits_taking'] = defaultCreditsStandard - tempVariable;
							}

							totalCredits = totalCredits - tempObject['credits_taking'];

						} else {
							return false;
						}

						

					} else if (tempTrackSpeed == 'accelerated') {

						if (tempStarDate == value.start_date) {

							if (totalCredits - firstSemesterMaxCreditsAccelerated >= 0) {
								tempObject['credits_taking'] = firstSemesterMaxCreditsAccelerated;
							} else {
								let tempVariable = totalCredits - firstSemesterMaxCreditsAccelerated;
								tempVariable = Math.abs(tempVariable); 
								tempObject['credits_taking'] = firstSemesterMaxCreditsAccelerated - tempVariable;
							}

							totalCredits = totalCredits - tempObject['credits_taking'];

						} else if (tempStarDate < value.start_date) {

							if (totalCredits - defaultCreditsAccelerated >= 0) {
								tempObject['credits_taking'] = defaultCreditsAccelerated;
							} else {
								let tempVariable = totalCredits - defaultCreditsAccelerated;
								tempVariable = Math.abs(tempVariable); 
								tempObject['credits_taking'] = defaultCreditsAccelerated - tempVariable;
							}

							totalCredits = totalCredits - tempObject['credits_taking'];

						} else {
							return false;
						}

					}
					// END CREDIT CALCULATIONS

					tempObject['credits_after'] = totalCredits;
					tempTrackArray.push(tempObject);
					

				});

				this.transformedTrack = tempTrackArray;

				return false;

			} else {
				return false;
			}

		},

		screenshot() {

			html2canvas(document.querySelector("#app")).then(canvas => {
			    let pdf = new jsPDF();
			    let marginLeft=20;
			    let marginRight=20;
			    let today = new Date();
			    let y = today.getFullYear();
			    let m = today.getMonth() + 1;
			    let d = today.getDate();
			    let h = today.getHours();
			    let mi = today.getMinutes();
			    let s = today.getSeconds();
			    let tempDate = y + "-" + m + "-" + d + "-" + h + "-" + mi + "-" + s;
			    pdf.addImage(canvas.toDataURL("image/jpeg"), 'JPEG', 15, 40, 180, 160);
			    pdf.save('mu-' + tempDate + '.pdf');

			});


		}

	}


});

