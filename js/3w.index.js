/**
* @author		Engelbert Chuwa <echuwa[at]unicef.org; chochote[at]gmail.com>
* @version		2.0 
* @since		August 2016 
 
* Changelog:	Improved perfomance using queue.js to implement concurrency.
*				Funding organisations, region and district information 
*				is now multi-valued (comma separated). 
*				Regional and District grouping revised to be more efficient.	
*/


//Global variables (Crossfilter instance, country map (regions), regional maps (districts))
var cf, countryMapData, regionMapData, activityData; 

//Global dimension and group variables
var activityDim, sectorDim, subsectorDim, fundingOrgDim, /* aOrgTypeDim, */ fOrgTypeDim, sectorFundingDim, regionDim, districtsDim, allGrp, activityDimGrp, sectorDimGrp, subsectorDimGrp, /* aOrgTypeDimGrp, */ fundingOrgDimGrp, fOrgTypeDimGrp, /* fundingByAccOrgGrp, */ sectorFundingDimGrp, uniqueActivityGrp;

//Global state and count variables
var countryMapState = {};
var mapDrilldownState = false;

//Global chart variables
var sector_chart, subsector_chart, funding_org_chart, /* aorgtype_chart, */ forgtype_chart, regtable_chart, funding_by_sector_chart;

var currencyQuotes;


//Wait for activity and exchange rates data load to finish before drawing charts
d3.queue(2)
	.defer(loadData)
	.defer(loadFxRates)
	.await(initialiseCharts);
	
/**
 * Function to fetch activity data (in JSON format) using a web service
 *
 * @param  
 * @return 
 */
function loadData(callback) {
	console.log("Loading Activity Data...");
	$.ajaxSetup({
	  cache: true //Needed to force getScript to fetch from cache. Default behavior is new load every time
	});
	
	$.ajax({
		url:'includes/fetchdata.php',
		success: function (response) {
			console.log('Great! Activity Data Fetch Successful.');
		},
		error: function () {
			console.log('Bummer! Activity Data Fetch Failed.');
		},
		complete: function () {//Call this with old data
			d3.json("data/data.json", function(data) {
				if (!data){
					var err = new Error('Activity Data Load Failed.');
					err.status = 404;
					return callback(err); 
				}  
				callback(null, data);
			});
		}
	});	
}

/**
 * Function to fetch exchange rates data (in JSON format) using a web service (http://apilayer.net/)
 *
 * @param  
 * @return 
 */
function loadFxRates(callback){
	console.log("Loading Forex Exchange Data...");
	$.ajaxSetup({
	  cache: true //Needed to force getScript to fetch from cache. Default behavior is new load every time
	});
	
	$.ajax({
		url:'includes/fetchforexrates.php',
		success: function (response) {
			console.log('Great! Forex Exchange Data Fetch Successful.');
		},
		error: function () {
			console.log('Bummer! Forex Exchange Data Fetch Failed.');
		},
		complete: function () {//Call this with old data
			d3.json("data/fxrates.json", function(data) {
				if (!data){
					var err = new Error('Forex Exchange Data Load Failed.');
					err.status = 404;
					return callback(err); 
				}  
				callback(null, data);
			});
		}
	});	
}

/**
 * Function implements the necessary initialisations (crossfilter dimensions, dc.js chart objects) for 
 * charting activity data
 *
 * @param  
 * @return 
 */
function initialiseCharts(error, data, fxdata) {
    console.log("Rendering charts.");

	//Create an object with fx quotes
	currencyQuotes = fxdata.quotes;
	
	activityData = data; //
	
	//get activity data into crossfilter
    cf = crossfilter(activityData); 
	
    try {
		 
        // Defining Dimensions
		
		//Get activities by region for counting purposes, to present on map which should reflect on colox axis
        regionDim = cf.dimension(function(d) {
			return d.Regions;
        }); 
		
        //Get activities by district for purposes of getting activity count per district
        districtsDim = cf.dimension(function(d) {
			return d.Districts;
        });

        //Get activities by unique Code. Logic to check for uniquness of activities to be implemented here
        activityDim = cf.dimension(function(d) {
            return d.ActivityCode;
        });

        //Get activities by sector
        sectorDim = cf.dimension(function(d) {
            return d.ActivityClassification;
        });

        //Funding by sector
        sectorFundingDim = cf.dimension(function(d) {
            return d.ActivityClassification;
        });

        //Get activities by subsectors for counting purposes
		subsectorDim = cf.dimension(function(d) { 
			//console.log("Subsector: " + d.ActivitySubClassification.trim());
			//	if (d.ActivitySubClassification.trim() !==""){
				//console.log("Returning " + d.ActivitySubClassification);
				return d.ActivitySubClassification;
			//}
		});

        //Get funding organisations by region for counting purposes
        fundingOrgDim = cf.dimension(function(d) {
            //if (d.FundingOrg !="")
				return d.FundingOrg;
        });

        
        //Funding Organisation type dimension
        fOrgTypeDim = cf.dimension(function(d) {
            /*if (d.FundingOrg !="")*/
            return d.OrgType;
        });

        //get funding amount
        fundingDim = cf.dimension(function(d) {
            //currency conversion logic below
			if (d.FundingCurrency=="USD"){
				return +d.FundingAmount;
			}
			else
			{	
				var convertedAmount = convertFx (+d.FundingAmount, d.FundingCurrency);
				return convertedAmount;
			}				
            
        });

        // dc.js Chart Initialisations
        sector_chart = dc.rowChart("#sectors");
		activity_counter = dc.numberDisplay("#activity_counter");
        subsector_chart = dc.rowChart("#subsectors");
        funding_org_chart = dc.rowChart("#fund_org");
        //aorgtype_chart = dc.pieChart("#aorgtype");
        forgtype_chart = dc.pieChart("#forgtype");
        regtable_chart = dc.dataTable("#regtable");
        funding_by_sector_chart = dc.rowChart("#funding");
        //funding_by_sector_chart = dc.barChart("#funding");
        //funding_by_org_chart = dc.barChart("#funding2");
		
		//crossfilter group of all data
        allGrp = cf.groupAll();
		
		//concurrent rendering of the charts (dc.js charts first and then map when done)
		d3.queue()
			.defer(drawActivityBySectorChart)
			.defer(drawSectorFundingChart)
			.defer(drawActivitiesBySubsectorChart)
			.defer(drawActivitiesByFundingOrganisationChart)
			//.defer(drawActivitiesByAccountableOrganisationTypeChart)
			.defer(drawActivitiesByFundingOrganisationTypeChart)
			.defer(drawActivityCounterDisplay)
			.defer(drawActivitiesByRegionTable)
			.await(function (err, res) {
				if(err !== null)
					console.log("An error occurred: " + err);
				//console.log(res);
				dc.renderAll();
				drawMap();
			});
	} //End of try
    catch (err) {
        console.log("An Error Occurred: " + err);
		return false;
        //exit(0);
    }
	
} //End of drawCharts	

/**
 * Function to create crossfilter group and draw activities by sector (classification) row chart 
 *
 * @param  
 * @return 
 */
function drawActivityBySectorChart(callback){
	 sectorDimGrp = sectorDim.group();
        /* sectorDimGrp = sectorDim.group().reduce(
            function(p, d) {
                if (d.ActivityCode in p.Activities) {
                    p.Activities[d.ActivityCode]++;
                } else {
                    p.Activities[d.ActivityCode] = 1;
                    p.ActivityCount++;
                }
                return p;
            },

            function(p, d) {
                p.Activities[d.ActivityCode]--;
                if (p.Activities[d.ActivityCode] === 0) {
                    delete p.Activities[d.ActivityCode];
                    p.ActivityCount--;
                }
                return p;
            },

            function() {
                return {
                    ActivityCount: 0,
                    Activities: {}
                };
            }
        ); */

		/*Sector Chart definition starts here */
        sector_chart.width(400).height(250)
            .dimension(sectorDim)
            .group(sectorDimGrp)
            //.renderLabel(true)
            .colors([
                '#4da6ff',
                '#EEEEEE',
            ])
            .colorDomain([0, 1])
            .colorAccessor(function(d, i) {
                return 0;
            })
            /* .valueAccessor(function(d) {
                 return d.value.ActivityCount;
            }) */
			.on('filtered.monitor', function(chart, filter) {
				updateMapOnFiltering();
			});
			
        sector_chart.xAxis().tickFormat(function(v) {
            return "";
        });
		callback(null, sector_chart);
}

/**
 * Function to create crossfilter group and draw funding by sector (classification) row chart 
 *
 * @param  
 * @return 
 */
function drawSectorFundingChart(callback){
	    //sector Funding group
        sectorFundingDimGrp = sectorFundingDim.group().reduce(
            function(p, d) {
                if (d.ActivityCode in p.Activities) {
                    p.Activities[d.ActivityCode]++;
                } else {
                    p.Activities[d.ActivityCode] = 1;
                    if ($.isNumeric(d.FundingAmount)) 
                        p.FundingAmountTotal += parseInt(convertFx (d.FundingAmount, d.FundingCurrency));
                }
                return p;
            },

            function(p, d) {
                p.Activities[d.ActivityCode]--;
                if (p.Activities[d.ActivityCode] === 0) {
                    delete p.Activities[d.ActivityCode];
                    if ($.isNumeric(d.FundingAmount))
                        p.FundingAmountTotal -= parseInt(convertFx (d.FundingAmount, d.FundingCurrency));
                }
                return p;
            },

            function() {
                return {
                    FundingAmountTotal: 0,
                    Activities: {}
                };
            }
        );
		
		/*Funding (Amount) by sector chart Starts Here */
        funding_by_sector_chart.width(400).height(250)
            //.margins({top: 20, left: 10, right: 10, bottom: 20})
            //.x(d3.scale.ordinal()) //for bar charts
            //.ordinalColors(['#3182bd', '#6baed6', '#9ecae1', '#c6dbef', '#dadaeb'])
            .dimension(sectorFundingDim)
            .group(sectorFundingDimGrp)
            .elasticX(true)
            //.gap(20)
            //.renderTitleLabel(true)
            .data(function(group) {
                return group.top(20);
            })
            //.ordering(function(d) { return -d.value })
            //.order(d3.ascending)
            .colors([
                '#4da6ff',
                '#EEEEEE',
            ])
            .colorDomain([0, 1])
            .colorAccessor(function(d, i) {
                return 0;
            })
            .valueAccessor(function(d) {
                //Thousands
                return Math.floor(+d.value.FundingAmountTotal / 1000);
            })
            .title(function(p) {
                return 'Sector: ' + p.key + '\n' +
                    'Funding (Actual): ' + d3.format("$,.0f")(+p.value.FundingAmountTotal);
            })
			.on('filtered.monitor', function(chart, filter) {
				updateMapOnFiltering();
			})
            .xAxis().ticks(4).tickFormat( /*d3.format("$.2s")*/ d3.format("$,.0f"));
			
			callback(null, funding_by_sector_chart);
}

/**
 * Function to create crossfilter group and draw activities by sub sector (sub classifications) row chart 
 *
 * @param  
 * @return 
 */
function drawActivitiesBySubsectorChart(callback){
	subsectorDimGrp = subsectorDim.group();
		
	/* Sub-sector Chart definition starts here */
	subsector_chart.width(400).height(250)
		.dimension(subsectorDim)
		.group(subsectorDimGrp)
		.elasticX(true)
		.data(function(group) {
			return group.top(15);
		})
		.colors([
			'#3399ff',
			'#EEEEEE',
		])
		.colorDomain([0, 1])
		.colorAccessor(function(d, i) {
			return 0;
		})
		.on('filtered.monitor', function(chart, filter) {
			updateMapOnFiltering();
		});
		
	subsector_chart.xAxis().tickFormat(function(v) {
		return "";
	});
	 
	
	callback(null, subsector_chart);
}

/**
 * Function to create crossfilter group and draw activities by organisation (funding) row chart 
 *
 * @param  
 * @return 
 */

function drawActivitiesByFundingOrganisationChart(callback){
	//fundingOrgDimGrp = fundingOrgDim.group();
    /* fundingOrgDimGrp = fundingOrgDim.group().reduce(
		function(p, d) {
			if (d.ActivityCode in p.Activities) p.Activities[d.ActivityCode]++;
			else {
				p.Activities[d.ActivityCode] = 1;
				p.ActivityCount++;
			}
			return p;
		},

		function(p, d) {
			p.Activities[d.ActivityCode]--;
			if (p.Activities[d.ActivityCode] === 0) {
				delete p.Activities[d.ActivityCode];
				p.ActivityCount--;
			}
			return p;
		},

		function() {
			return {
				ActivityCount: 0,
				Activities: {}
			};
		}
	); */
	
	fundingOrgDimGrp = fundingOrgDim.groupAll().reduce(
		function(p, d) {		
			if (d.FundingOrg[0] === "") return p;
			$.each(d.FundingOrg.split(', '), function(idx, val) {
				p[val] = (p[val] || 0) + 1; //increment counts
			})
			return p;
		},

		function(p, d) {
			if (d.FundingOrg[0] === "") return p;    // skip empty values
				$.each(d.FundingOrg.split(', '), function(idx, val) {
					p[val] = (p[val] || 0) - 1; //decrement counts
				})
			return p;
		},

		function() {
			return {}; 
		}
	).value(); 
	
	fundingOrgDimGrp.all = function() {
		var newObject = [];
		for (var key in this) {
			if (this.hasOwnProperty(key) && key != "all") {
				newObject.push({
					key: key,
					value: {ActivityCount: this[key]}
				});
			}
		}
		return newObject;
	}  

	
	/* Funding Organisation Chart starts here */
	funding_org_chart.width(400).height(250)
		.dimension(fundingOrgDim)
		.group(fundingOrgDimGrp)
		.elasticX(true)
		//.xAxisLabel('000')
		.colors([
			'#3399ff',
			'#EEEEEE',
		])
		.colorDomain([0, 1])
		.colorAccessor(function(d, i) {
			return 0;
		})
		.valueAccessor(function(d) {
			return d.value.ActivityCount;
		})
		.data(function(group) {
			//return group.top(15);
			return group.all().filter(function(kv) {
				//console.log(kv);
				return kv.key != "";
			});
		})
		.on('filtered.monitor', function(chart, filter) {
			updateMapOnFiltering();
		});
		
	funding_org_chart.xAxis().tickFormat(function(v) {
		return "";
	});
	
	//Custom filter handler to deal with multi-valued data
	funding_org_chart.filterHandler (function (dimension, filters) {
		dimension.filter(null); //clear filter   
		if (filters.length === 0)
			dimension.filter(null); //clear filter
		else
			dimension.filterFunction(function (d) {
				return findOne (d.split(', '), filters);
			});
		return filters; 
	}); 
	
	callback(null, funding_org_chart);
}



/**
 * Function to create crossfilter group and draw activities by organisation type (funding) row chart 
 *
 * @param  
 * @return 
 */
function drawActivitiesByFundingOrganisationTypeChart (callback) {
        //fOrgTypeDimGrp = fOrgTypeDim.group();
        fOrgTypeDimGrp = fOrgTypeDim.group().reduce(
            function(p, d) {
                if (d.ActivityCode in p.Activities) p.Activities[d.ActivityCode]++;
                else {
                    p.Activities[d.ActivityCode] = 1;
                    p.ActivityCount++;
                }
                return p;
            },

            function(p, d) {
                p.Activities[d.ActivityCode]--;
                if (p.Activities[d.ActivityCode] === 0) {
                    delete p.Activities[d.ActivityCode];
                    p.ActivityCount--;
                }
                return p;
            },

            function() {
                return {
                    ActivityCount: 0,
                    Activities: {}
                };
            }
        );
		
		/*Funding Organisation type pie chart starts here*/
        forgtype_chart.width(180).height(180)
            .dimension(fOrgTypeDim)
            .group(fOrgTypeDimGrp)
            .renderLabel(true)
            .colors([
                '#b3e2cd',
                '#fdcdac',
                '#cbd5e8',
                '#f4cae4',
                '#e6f5c9',
                '#fff2ae'
            ])
            .colorDomain([0, 6])
            .colorAccessor(function(d, i) {
                return i % 5;
            })
            .valueAccessor(function(d) {
                return d.value.ActivityCount;
            })
			.on('filtered.monitor', function(chart, filter) {
				updateMapOnFiltering();
			});
			
			callback(null, forgtype_chart);
}

/**
 * Function to create crossfilter group and draw activity counter display  
 *
 * @param  
 * @return 
 */
function drawActivityCounterDisplay (callback){		
		uniqueActivityGrp = cf.groupAll().reduce(
			function(p, d) {
			    if (d.ActivityCode in p.Activities) p.Activities[d.ActivityCode]++;
                else {
                    p.Activities[d.ActivityCode] = 1;
                    p.ActivityCount++;
					
					if ($.isNumeric(d.FundingAmount)) 
                        p.FundingAmountTotal += parseInt(convertFx (d.FundingAmount, d.FundingCurrency));
                }
                return p;
            },

            function(p, d) {
                p.Activities[d.ActivityCode]--;
                if (p.Activities[d.ActivityCode] === 0) {
                    delete p.Activities[d.ActivityCode];
                    p.ActivityCount--;
					
					if ($.isNumeric(d.FundingAmount))
                        p.FundingAmountTotal -= parseInt(convertFx (d.FundingAmount, d.FundingCurrency));
                }
                return p;
            },

            function() {
                return {
                    ActivityCount: 0,
					FundingAmountTotal: 0,
                    Activities: {}
                };
            }
		);
		
		/*Activity counter display*/
		activity_counter
			.formatNumber(d3.format("d"))
			.valueAccessor(function(d){return d.ActivityCount; })
			.group(/*all*/uniqueActivityGrp);
		callback(null, activity_counter);
}

/**
 * Function to create crossfilter group and draw activity datatable  
 *
 * @param  
 * @return 
 */
function drawActivitiesByRegionTable(callback){
	 /*Region table chart definition starts here*/
        regtable_chart.width(180).height(180)
            .dimension(activityDim)
            .group(function(d) {
				//return d.rollup.ActivityClassification;
                return 0;
            })
            .size(Infinity)
            .columns(['',
                {
                    label: 'Title',
                    format: function(d) {
                        return d.ActivityTitle;
                    }
                },
                '',
                {
                    label: 'Classification',
                    format: function(d) {
                        if (d.ActivitySubClassification.length > 0)
                            return d.ActivityClassification + ' (' + d.ActivitySubClassification + ')';
                        else
                            return d.ActivityClassification;
                    }
                },
                '',
                {
                    label: 'Funding Organisation',
                    format: function(d) {
                        return d.FundingOrg;
                    }
                },
                '',
                {
                    label: 'Description',
                    format: function(d) {
                        return d.ActivityDesc;;
                    }
                }
            ])
            .showGroups(false)
            //.sortBy(function (d) { return d.FundingAmount; })
            .order(d3.ascending);
		//update();
		callback(null, regtable_chart);
}

/**
 * Function to create crossfilter group and draw Map  
 *
 * @param  
 * @return 
 */
function drawMap (callback){
	countryMapData = Highcharts.geojson(Highcharts.maps['countries/tz/tz-all']),
		
		// Some responsiveness
        small = $('#map_container').width() < 700;
		
		regionalActivityGrp = /* regionDim */cf.groupAll().reduce(
			function(p, d) {
				if (d.Regions[0] === "") return p;
				$.each(d.Regions.split(', '), function(idx, val) {
					if (typeof(p[val]) === "undefined") {
						p[val] = {};
						p[val].RegionalActivities = [];
						p[val].RegionalActivityCount = 0;
						p[val].RegionalFundingOrgs = [];
						p[val].RegionalFundingOrgCount = 0;
						p[val].RegionalFundingAmountTotal = 0;			
					} 	
					
					p[val].RegionalActivities.push(d.ActivityCode);
					p[val].RegionalActivityCount = (p[val].RegionalActivityCount || 0) + 1;
						
					if ($.isNumeric(d.FundingAmount)) {
						p[val].RegionalFundingAmountTotal += parseInt(convertFx (d.FundingAmount, d.FundingCurrency));
					}
						
					if(d.FundingOrg !==""){
						if (d.FundingOrg in p[val].RegionalFundingOrgs) { 
							//p.RegionalFundingOrgs[d.FundingOrg]++;
							//p[val].RegionalFundingOrgs.push(d.FundingOrg);
						}
						else {
							p[val].RegionalFundingOrgs.push(d.FundingOrg);
							p[val].RegionalFundingOrgCount++;
						}
					}
				})
				return p;
			},

			function(p, d) {
				if (d.Regions[0] === "") return p;    // skip empty values
					$.each(d.Regions.split(', '), function(idx, val) {
						if (typeof(p[val]) === "undefined") {
							
						} else {
							if (d.ActivityCode in p[val].RegionalActivities) {
								//p[val].RegionalActivities[d.ActivityCode]++;
							} else {
								delete p[val].RegionalActivities[d.ActivityCode];
								p[val].RegionalActivityCount--;
									
								if ($.isNumeric(d.FundingAmount)) {
									p[val].RegionalFundingAmountTotal -= parseInt(convertFx (d.FundingAmount, d.FundingCurrency));
								}
							}

							if(d.FundingOrg !==""){
								if (d.FundingOrg in p[val].RegionalFundingOrgs) { 
									
								}
								else {
									delete p[val].RegionalFundingOrgs[d.FundingOrg];
									p[val].RegionalFundingOrgCount--;
								}
							}
						}		
					})
				return p;
			},

			function() {
				return {};    
			}
			);//.value();  

		// Set drilldown pointers and get activity count for each region
        $.each(countryMapData, function(i) {
			this.drilldown = this.properties['hc-key'];
            var curRegion = this.properties['name'];
						
			if (typeof regionalActivityGrp.value()[curRegion] !== "undefined"){
				this.value = regionalActivityGrp.value()[curRegion].RegionalActivityCount;
				this.value2 = regionalActivityGrp.value()[curRegion].RegionalFundingOrgCount;
				this.value3 = regionalActivityGrp.value()[curRegion].RegionalFundingAmountTotal;	
			}
			else {
				this.value = 0;
				this.value2 = 0;
				this.value3 = 0;	
			}
        });

        //Clear filters for the other charts to continue worrking
        districtsDim.filterAll();
        regionDim.filterAll();

        // Instantiate the map
		Highcharts.mapChart('map_container', {
            chart: {
                events: {
                    drilldown: function(e) {
                        if (!e.seriesOptions) {
                            mapDrilldownState = true;
                       	
							//regionDim needs a custom filter function to handlle multi-value data;
							regionDim.filterFunction(function(d) { 
								return (d.indexOf(e.point.name) !== -1); 
								/* return d.includes(e.point.name); */
							});
							
							dc.redrawAll();

                            update();
                            var chart = this,
                                mapKey = e.point.drilldown + '-all',
                                // Handle error, the timeout is cleared on success
                                fail = setTimeout(function() {
                                    if (!Highcharts.maps[mapKey]) {
                                        chart.showLoading('<i class="icon-frown"></i>Loading of ' + e.point.name + ' map taking longer than unusual');
                                        fail = setTimeout(function() {
                                            chart.hideLoading();
                                        }, 2000);
                                    }
                                }, 3000);

                            // Show the Font Awesome spinner
                            chart.showLoading('<i class="icon-spinner icon-spin icon-3x"></i>'); 

                            $('.locality').html('in ' + e.point.name);
                            $("#table_container").show();

                            // Load the drilldown map
                            $.getScript('data/' + mapKey + '.js', function(){
								regionMapData = Highcharts.geojson(Highcharts.maps[mapKey]);
								
								districtActivityGrp = /* regionDim */cf.groupAll().reduce(
									function(p, d) {
										if (d.Districts[0] === "") return p;
										$.each(d.Districts.split(', '), function(idx, val) {
											if (typeof(p[val]) === "undefined") {
												p[val] = {};
												p[val].DistrictActivities = [];
												p[val].DistrictActivityCount = 0;
												p[val].DistrictFundingOrgs = [];
												p[val].DistrictFundingOrgCount = 0;
												p[val].DistrictFundingAmountTotal = 0; 
											} 
											
											p[val].DistrictActivities.push(d.ActivityCode);
											p[val].DistrictActivityCount = (p[val].RegionalActivityCount || 0) + 1;
														
											if ($.isNumeric(d.FundingAmount)) {
												p[val].DistrictFundingAmountTotal += parseInt(convertFx (d.FundingAmount, d.FundingCurrency));
											}
																				
											if(d.FundingOrg !==""){
												if (d.FundingOrg in p[val].DistrictFundingOrgs) { 
													
												}
												else {
													p[val].DistrictFundingOrgs.push(d.FundingOrg);
													p[val].DistrictFundingOrgCount++;
												}
											}		
										})
										return p;
									},

									function(p, d) {
										if (d.Districts[0] === "") return p;    // skip empty values
											$.each(d.Districts.split(', '), function(idx, val){
												if (typeof(p[val]) === "undefined") {
														
												} else {
													if (d.ActivityCode in p[val].DistrictActivities) {
														//p[val].RegionalActivities[d.ActivityCode]++;
													} else {
														delete p[val].DistrictActivities[d.ActivityCode];
														p[val].DistrictActivityCount--;
															
														if ($.isNumeric(d.FundingAmount)) {
															p[val].DistrictFundingAmountTotal -= parseInt(convertFx (d.FundingAmount, d.FundingCurrency));
														}
													}
													
													if(d.FundingOrg !==""){
														if (d.FundingOrg in p[val].DistrictFundingOrgs) { 
															
														}
														else {
															delete p[val].DistrictFundingOrgs[d.FundingOrg];
															p[val].DistrictFundingOrgCount--;
														}
													}
												}		
											})
										return p;
									},

									function() {
										return {};  
									}
								);
								
                                $.each(regionMapData, function(i) {
									var curDistrict = this.properties['name'];
							
									if (typeof districtActivityGrp.value()[curDistrict] !== "undefined"){
										this.value = districtActivityGrp.value()[curDistrict].DistrictActivityCount;
										this.value2 = districtActivityGrp.value()[curDistrict].DistrictFundingOrgCount;
										this.value3 = districtActivityGrp.value()[curDistrict].DistrictFundingAmountTotal;
									}
									else {
										this.value = 0;
										this.value2 = 0;
										this.value3 = 0;	
									}
                                });

                                districtsDim.filterAll(); //Clear filter for the other charts to continue working

                                // Hide loading and add series
                                chart.hideLoading();
                                clearTimeout(fail);
								
                                chart.addSeriesAsDrilldown(e.point, {
                                    name: e.point.name,
                                    data: regionMapData,
                                    dataLabels: {
                                        enabled: true,
                                        format: '{point.name}'
                                    },
                                    events: {
                                        click: function(ev) {
                                            $('.locality').html('in ' + ev.point.name);
											
											//districtsDim needs a custom filter function to handlle multi-value data;
											districtsDim.filterFunction(function(d) { 
												return (d.indexOf(ev.point.name) !== -1); 
												/* return d.includes(e.point.name); */
											});
											
                                            countryMapState['districtState'] = ev.point.name;
                                            update();
                                            dc.redrawAll();
                                        }
                                    },
                                });
                            }).fail(function() {
                                chart.showLoading('<i class="icon-frown"></i>' + e.point.name + ' Map Missing');
                                //clearTimeout(fail);
                            })
                        }
                        //Set title of child map to region (don't redraw)
                        this.setTitle(null, {
                            text: e.point.name
                        }, false);
                    },
                    drillup: function() {
                        //clear title of map and clear filter then redraw all charts
                        this.setTitle(null, {
                            text: ''
                        }, true);
                        mapDrilldownState = false;
                        $('.locality').html('');
                        $("#table_container").hide();
                        regionDim.filterAll();
                        districtsDim.filterAll();
                        //FundingOrgDim.filterAll();
                        //activityDim.filterAll();
                        //dc.filterAll();
                        countryMapState['regionState'] = '';
                        dc.redrawAll();
                        //needed reCalcValues(false);
                    },
                    redraw: function(e) {  
						//to distinguish between when redraw was called at drill up and when it was called at drillup
						/* console.log("Redraw called");
						console.log(e.target.drilldownLevels);
						console.trace(); 
						console.log(this);
						console.log(e.target);*/
						if (typeof (e.target.drilldownLevels) === "undefined") {
							//Redraw on filter at start (country map)(non map filters)
							return 0;
						} else if (e.target.drilldownLevels.length <= 0) {
							//Redraw on drillUp
							regionalActivityGrp = /* regionDim */cf.groupAll().reduce(
								function(p, d) {
									if (d.Regions[0] === "") return p;
									$.each(d.Regions.split(', '), function(idx, val) {
										if (typeof(p[val]) === "undefined") {
											p[val] = {};
											p[val].RegionalActivities = [];
											p[val].RegionalActivityCount = 0;
											p[val].RegionalFundingOrgs = [];
											p[val].RegionalFundingOrgCount = 0;
											p[val].RegionalFundingAmountTotal = 0; 
										} 
										
										p[val].RegionalActivities.push(d.ActivityCode);
										p[val].RegionalActivityCount = (p[val].RegionalActivityCount || 0) + 1;
													
										if ($.isNumeric(d.FundingAmount)) {
											p[val].RegionalFundingAmountTotal += parseInt(convertFx (d.FundingAmount, d.FundingCurrency));
										}
											
										if(d.FundingOrg !==""){
											if (d.FundingOrg in p[val].RegionalFundingOrgs) { 
											
											}
											else {
												p[val].RegionalFundingOrgs.push(d.FundingOrg);
												p[val].RegionalFundingOrgCount++;
											}
										}
									})
									return p;
								},

								function(p, d) {
									if (d.Regions[0] === "") return p;    // skip empty values
										$.each(d.Regions.split(', '), function(idx, val) {
											
										if (typeof(p[val]) === "undefined") {
																							
										} else {
											if (d.ActivityCode in p[val].RegionalActivities) {
										
											} else {
												delete p[val].RegionalActivities[d.ActivityCode];
												p[val].RegionalActivityCount--;
													
												if ($.isNumeric(d.FundingAmount)) {
													p[val].RegionalFundingAmountTotal -= parseInt(convertFx (d.FundingAmount, d.FundingCurrency));
												}
											}
											
											if(d.FundingOrg !==""){
												if (d.FundingOrg in p[val].RegionalFundingOrgs){ 
													
												}
												else {
													delete p[val].RegionalFundingOrgs[d.FundingOrg];
													p[val].RegionalFundingOrgCount--;
												}
											}
										}
									})
									return p;
								},

								function() {
									return {};   
								}
							);
							
							$.each(countryMapData, function(i) {
								try{
									redrawValue1 = 0;
									redrawValue2 = 0;
									redrawValue3 = 0;
									
									if (typeof (regionalActivityGrp.value()[this.properties['name']]) !== "undefined"){
										redrawValue1 = regionalActivityGrp.value()[this.properties['name']].RegionalActivityCount;
										redrawValue2 = regionalActivityGrp.value()[this.properties['name']].RegionalFundingOrgCount;
										redrawValue3 = regionalActivityGrp.value()[this.properties['name']].RegionalFundingAmountTotal;
									} 
									
									 $('#map_container').highcharts().series[0].points[$('#map_container').highcharts().series[0].points.map(function(element) {
										return element.name;
									}).indexOf(this.properties['name'])].update({
										value: redrawValue1
									}, false);
									
									$('#map_container').highcharts().series[0].points[$('#map_container').highcharts().series[0].points.map(function(element) {
										return element.name;
									}).indexOf(this.properties['name'])].update({
										value2: redrawValue2
									}, false);
									
									$('#map_container').highcharts().series[0].points[$('#map_container').highcharts().series[0].points.map(function(element) {
										return element.name;
									}).indexOf(this.properties['name'])].update({
										value3: redrawValue3
									}, false); 
								
									
								} catch (err) {
									console.log("Error: " + err);
								}
							});
						}
                    }
                }
            },

            colors: ['#EEEEEE', '#3399ff', '#4da6ff', '#66b3ff', '#80bfff', '#99ccff', '#b3d9ff', '#cce6ff', '#eeeeee'],

            title: {
                text: ''
            },
			
			lang: {
                drillUpText: 'Back to Country'
            },

            legend: small ? {} : {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                enabled: false
            },

            colorAxis: {
                min: 0,
                /*minColor: '#E6E7E8',
                maxColor: '#7FC1FF'*/
                minColor: '#E6E7E8',
                maxColor: '#CCE6FF'
            },

            mapNavigation: {
                enabled: true,
                buttonOptions: {
                    verticalAlign: 'bottom',
                    align: 'left',
                    alignTo: 'spacingBox',
                    x: 30

                }
            },

            credits: false,

            plotOptions: {
                map: {
                    states: {
                        hover: {
                            color: '#d6d6d6'
                        }
                    },
                    borderColor: '#9E9E9E',
                    borderWidth: '1'
                }
            },

            series: [{
                data: countryMapData,
                name: 'Tanzania',
                dataLabels: {
                    enabled: true,
                    shadow: false,
                    style: {
                        "color": "contrast",
                        "opacity": "1.0",
                        "fontSize": "9px",
                        "white-space": "nowrap",
                        "display": "block",
                        "font-family": "Roboto, sans-serif",
                        "font-weight": "normal",
                        "color": "#222"
                    },
                    format: '{point.name}'
                }
            }],

            tooltip: {
                enabled: true,
                formatter: function() {
                    if (this.point.drilldown !== undefined) {
                        return '<b>Region:</b> ' + this.point.name + '<br>' +
                            '<b>Activity Count:</b> ' + this.point.value + '<br>' +
                            '<b>Funding Organisation Count:</b> ' + this.point.value2 + '<br>' +
                            '<b>Total Funding:</b> ' + d3.format("$,.0f")(this.point.value3);
                    } else {
                        return '<b>District:</b> ' + this.point.name + '<br>' +
                            '<b>Activity Count:</b> ' + this.point.value + '<br>' +
                            '<b>Funding Organisation Count:</b> ' + this.point.value2 + '<br>' +
                            '<b>Total Funding:</b> ' + d3.format("$,.0f")(this.point.value3);
                    }
                }
            },

            drilldown: {
                activeDataLabelStyle: {
                    color: '#222',
                    textDecoration: 'none'
                },

                drillUpButton: {
                    relativeTo: 'spacingBox',
                    position: {
                        x: -50,
                        y: 60
                    }
                }
            }
        });
		
		//callback(null, regtable_chart);
		//callback(null, 'map_container');
}

/**
 * Function run recounts nd update map when filters are applied  
 *
 * @param  
 * @return 
 */
function updateMapOnFiltering () {
	if (!mapDrilldownState) {
			regionalActivityGrp = /* regionDim */cf.groupAll().reduce(
				function(p, d) {
					if (d.Regions[0] === "") return p;
					$.each(d.Regions.split(', '), function(idx, val) {
						if (typeof(p[val]) === "undefined") {
							p[val] = {};
							p[val].RegionalActivities = [];
							p[val].RegionalActivityCount = 0;
							p[val].RegionalFundingOrgs = [];
							p[val].RegionalFundingOrgCount = 0;
							p[val].RegionalFundingAmountTotal = 0; 
						} 
						
						p[val].RegionalActivities.push(d.ActivityCode);
						p[val].RegionalActivityCount = (p[val].RegionalActivityCount || 0) + 1;
									
						if ($.isNumeric(d.FundingAmount)) {
							p[val].RegionalFundingAmountTotal += parseInt(convertFx (d.FundingAmount, d.FundingCurrency));
						}
								
						if(d.FundingOrg !==""){
							if (d.FundingOrg in p[val].RegionalFundingOrgs) { 
						
							}
							else {
								p[val].RegionalFundingOrgs.push(d.FundingOrg);
								p[val].RegionalFundingOrgCount++;
							}
						}	
					})
					return p;
				},

				function(p, d) {
					if (d.Regions[0] === "") return p;    // skip empty values
						$.each(d.Regions.split(', '), function(idx, val) {
			
						if (typeof(p[val]) === "undefined") {
									
						} else {
							if (d.ActivityCode in p[val].RegionalActivities) {
							
							} else {
								delete p[val].RegionalActivities[d.ActivityCode];
								p[val].RegionalActivityCount--;
									
								if ($.isNumeric(d.FundingAmount)) {
									p[val].RegionalFundingAmountTotal -= parseInt(convertFx (d.FundingAmount, d.FundingCurrency));
								}
							}
							
							if(d.FundingOrg !==""){
								if (d.FundingOrg in p[val].RegionalFundingOrgs) { 
									
								}
								else {
									delete p[val].RegionalFundingOrgs[d.FundingOrg];
									p[val].RegionalFundingOrgCount--;
								}
							}
						}		
					})
					return p;
				},

				function() {
					return {}; 
				}
			);
			
			$.each(countryMapData, function(i) {
				try{					
					filteredValue1 = 0;
					filteredValue2 = 0;
					filteredValue3 = 0;
					
					if (typeof (regionalActivityGrp.value()[this.properties['name']]) !== "undefined"){
						filteredValue1 = regionalActivityGrp.value()[this.properties['name']].RegionalActivityCount;
						filteredValue2 = regionalActivityGrp.value()[this.properties['name']].RegionalFundingOrgCount;
						filteredValue3 = regionalActivityGrp.value()[this.properties['name']].RegionalFundingAmountTotal;
					}  
					
					$('#map_container').highcharts().series[0].points[$('#map_container').highcharts().series[0].points.map(function  (element) {return element.name;}).indexOf(this.properties['name'])].update({value:filteredValue1 },false);
											
					$('#map_container').highcharts().series[0].points[$('#map_container').highcharts().series[0].points.map(function  (element) {return element.name;}).indexOf(this.properties['name'])].update({value2:filteredValue2 },false);
					
					$('#map_container').highcharts().series[0].points[$('#map_container').highcharts().series[0].points.map(function  (element) {return element.name;}).indexOf(this.properties['name'])].update({value3:filteredValue3 },false);
				
				} catch (err) {
					console.log("Error " + err);
				}
				regionDim.filterAll();

			});
		} else {
			//needed reCalcValues(true);
			
			districtActivityGrp = /* regionDim */cf.groupAll().reduce(
				function(p, d) {
					if (d.Districts[0] === "") return p;
					$.each(d.Districts.split(', '), function(idx, val) {
						if (typeof(p[val]) === "undefined") {
							p[val] = {};
							p[val].DistrictActivities = [];
							p[val].DistrictActivityCount = 0;
							p[val].DistrictFundingOrgs = [];
							p[val].DistrictFundingOrgCount = 0;
							p[val].DistrictFundingAmountTotal = 0; 
						} 
		
						p[val].DistrictActivities.push(d.ActivityCode);
						p[val].DistrictActivityCount = (p[val].RegionalActivityCount || 0) + 1;
									
						if ($.isNumeric(d.FundingAmount)) {
							p[val].DistrictFundingAmountTotal += parseInt(convertFx (d.FundingAmount, d.FundingCurrency));
						}
							
						if(d.FundingOrg !==""){
							if (d.FundingOrg in p[val].DistrictFundingOrgs) { 
									
							}
							else {
								p[val].DistrictFundingOrgs.push(d.FundingOrg);
								p[val].DistrictFundingOrgCount++;
							}
						}
					})
					return p;
				},

				function(p, d) {
					if (d.Districts[0] === "") return p;    // skip empty values
						$.each(d.Districts.split(', '), function(idx, val) {
						
						if (typeof(p[val]) === "undefined") {
									
						} else {
							if (d.ActivityCode in p[val].DistrictActivities) {
						
							} else {
								delete p[val].DistrictActivities[d.ActivityCode];
								p[val].DistrictActivityCount--;
									
								if ($.isNumeric(d.FundingAmount)) {
									p[val].DistrictFundingAmountTotal -= parseInt(convertFx (d.FundingAmount, d.FundingCurrency));
								}
							}
							
							if(d.FundingOrg !==""){
								if (d.FundingOrg in p[val].DistrictFundingOrgs) { 
									
								}
								else {
									delete p[val].DistrictFundingOrgs[d.FundingOrg];
									p[val].DistrictFundingOrgCount--;
								}
							}
						}		
					})
					return p;
				},

				function() {
					return {};  
				}
			);
							
			$.each(regionMapData, function(i) {
				try {
						distFilteredValue1 = 0;
						distFilteredValue2 = 0;
						distFilteredValue3 = 0;
						
						if (typeof districtActivityGrp.value()[this.properties['name']] !== "undefined") {
							distFilteredValue1 = districtActivityGrp.value()[this.properties['name']].DistrictActivityCount;
							distFilteredValue2 = districtActivityGrp.value()[this.properties['name']].DistrictFundingOrgCount;
							distFilteredValue3 = districtActivityGrp.value()[this.properties['name']].DistrictFundingAmountTotal;	
						}
					
					$('#map_container').highcharts().series[0].points[$('#map_container').highcharts().series[0].points.map(function(element) {
						return element.name;
					}).indexOf(this.properties['name'])].update({
						value: distFilteredValue1
					}, false);
					
					$('#map_container').highcharts().series[0].points[$('#map_container').highcharts().series[0].points.map(function(element) {
						return element.name;
					}).indexOf(this.properties['name'])].update({
						value2: distFilteredValue2
					}, false);
					
					$('#map_container').highcharts().series[0].points[$('#map_container').highcharts().series[0].points.map(function(element) {
						return element.name;
					}).indexOf(this.properties['name'])].update({
						value3: distFilteredValue3
					}, false);
					
				} catch (err) {
					console.log("Error: " + err);
				}
				
				districtsDim.filterAll();
			});
		}
		
	$('#map_container').highcharts().redraw();
}


/**
 * Function to download (export) all activity data as a CSV file  
 *
 * @param  
 * @return 
 */
function downloadAll () { 			
  var downloadData = activityData.map(function(d) {
		var row = {};
		for (var key in d) {
		  if (d.hasOwnProperty(key)) {
			row[key] = d[key];
		  }
		}
		return row;
	});
	
	var blob = new Blob([d3.csv.format(downloadData)], {type: "text/csv;charset=utf-8"});
	saveAs(blob, 'output_all.csv');
}

/**
 * Function to download (export) filtered activity data as a CSV file  
 *
 * @param  
 * @return 
 */
function download() {
  var downloadableData = regionDim.top(Infinity); //This needs changing to reflect all active filters
  downloadableData = downloadableData.map(function(d) {
		var row = {};
		regtable_chart.columns().forEach(function(c) {
			row[regtable_chart._doColumnHeaderFormat(c)] = regtable_chart._doColumnValueFormat(c, d);
		});
		return row;
	});
	
	var blob = new Blob([d3.csv.format(downloadableData)], {type: "text/csv;charset=utf-8"});
	saveAs(blob, 'output_filtered.csv');
}

/**
 * Function to reset filters   
 *
 * @param  
 * @return 
 */
function resetAll() {
	dc.filterAll(); 
	dc.renderAll(); 
	
	if (mapDrilldownState)
	{
		try {
			mapDrilldownState = false;
			$('#map_container').highcharts().drillUp();
		}
		catch(err) {
			console.log("Can not drill up any more " + err);
		}
	}
	
	//needed reCalcValues(false);
	return false;
}


/**
 * The following four functions are specific for handling pagination for regtable_chart
 */
 
// use odd page size to show the effect better
var ofs = 0, pag = 5, size = 0;	

/**
 * Function to update DOM text for regtable_chart pagination labels (x of y) 
 *
 * @param  
 * @return 
 */
function display() {
  d3.select('#begin')
	  .text(ofs+1);
  d3.select('#end')
	  //.text(ofs+pag-1 > size ? size: ofs+pag-1 ); 
	  .text(ofs+pag > size ? size: ofs+pag ); 
  d3.select('#last')
	  .attr('disabled', ofs-pag<0 ? 'true' : null);
  d3.select('#next')
	  .attr('disabled', ofs+pag>= size? 'true' : null);
  d3.select('#size').text(size);
  
}

/**
 * Function to update what regtable_chart currently displaces based on offset and page size 
 *
 * @param  
 * @return 
 */
function update() {
  size = //regionDim.top(Infinity).length;
		 //cf.size();
		 allGrp.value();
  regtable_chart.beginSlice(ofs);
  regtable_chart.endSlice(ofs+pag);
  display();
}

/**
 * Function to handle forward(next) page turns 
 *
 * @param  
 * @return 
 */
function next() {
  ofs += pag;
  update();
  regtable_chart.redraw();
}

/**
 * Function to handle backward(previous) page turns 
 *
 * @param  
 * @return 
 */
function last() {
  ofs -= pag;
  update();
  regtable_chart.redraw();
}
  
/**
 * Utility function to convert a currency amount to its USD equivalent using apilayer.net rates
 *
 * @param  
 * @return 
 */ 
function convertFx (amount, srcCurrency){
	fxRateFactor = currencyQuotes['USD'+srcCurrency]; 
	return amount/fxRateFactor;
}

/**
 * Function to determine if an array contains one or more items from another array.
 * 
 * @param {array} haystack the array to search.
 * @param {array} arr the array providing items to check for in the haystack.
 * @return {boolean} true|false if haystack contains at least one item from arr. 
 * (c) http://stackoverflow.com/questions/16312528/check-if-an-array-contains-any-element-of-another-array-in-javascript
 */ 
function findOne (haystack, arr) {
	return arr.some(v => haystack.includes(v))
    /* return arr.some(function (v) {
        return haystack.indexOf(v) >= 0;
    }); */
}
  
/**
 * Function implementing bookmarking chart filters status
 * Serializing filters values in URL (Not in use as of v2.0)
 *
 * @param  
 * @return 
 */ 
function getFiltersValues() {	
	var filters = [
		{ name: 'sector', value: sector_chart.filters()},
		{ name: 'subsector', value: subsector_chart.filters()},
		{ name: 'fundorg', value: funding_org_chart.filters()},
		//{ name: 'accorgtype', value: aorgtype_chart.filters()},
		{ name: 'fundorgtype', value: forgtype_chart.filters()},
		{ name: 'fundbysector', value: funding_by_sector_chart.filters()} 
		//,{ name: 'map', value: map2_chart.filters()}
		];

	var recursiveEncoded = $.param( filters );
	location.hash = recursiveEncoded;
}

/**
 * Utility function to count unique members of n arry (Not in use as of v2.0)
 *
 * @param  arr: Array to count from 
 * @param  key: Array values to check count of
 * @return 
 */ 
function getUniqueCount(arr, key){
	var unique = {};
	var distinct = [];
	for( var i in arr ){
	 if( typeof(unique[arr[i][key]]) == "undefined"){
	  distinct.push(arr[i][key]);
	 }
	 unique[arr[i][key]] = 0;
	}
  return distinct.length;
}

