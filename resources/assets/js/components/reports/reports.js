const
  ChartJs = require('chart.js'),
  money = require('money-math'),
  loading = require('../../vendor/vue-loading'),
  loadAwesome = require('../../libraries/load-awesome'),
  randomColor = require('randomcolor');

Vue.component('reports', {
  
  directives: {
    loading
  },

  computed: {
    storeStatsChartData() {
      return {
        labels: [
          Lang.get('labels.january')+' '+this.filters.year,
          Lang.get('labels.february')+' '+this.filters.year,
          Lang.get('labels.march')+' '+this.filters.year,
          Lang.get('labels.april')+' '+this.filters.year,
          Lang.get('labels.may')+' '+this.filters.year,
          Lang.get('labels.june')+' '+this.filters.year,
          Lang.get('labels.july')+' '+this.filters.year,
          Lang.get('labels.august')+' '+this.filters.year,
          Lang.get('labels.september')+' '+this.filters.year,
          Lang.get('labels.october')+' '+this.filters.year,
          Lang.get('labels.november')+' '+this.filters.year,
          Lang.get('labels.december')+' '+this.filters.year
        ],
        datasets: [
          {
            label: Lang.get('labels.orders'),
            type:'line',
            data: this.ordersData,
            fill: false,
            borderColor: '#d9534f',
            backgroundColor: '#d9534f',
            pointBorderColor: '#d9534f',
            pointBackgroundColor: '#d9534f',
            pointHoverBackgroundColor: '#d9534f',
            pointHoverBorderColor: '#d9534f',
            yAxisID: 'y-axis-orders'
          },
          {
            type: 'bar',
            label: Lang.get('labels.total'),
            data: this.incomeData,
            fill: false,
            backgroundColor: '#337ab7',
            borderColor: '#337ab7',
            hoverBackgroundColor: '#3097D1',
            hoverBorderColor: '#3097D1',
            yAxisID: 'y-axis-total'
          }
        ]
      }
    },
    
    popularProductsChartData() {
      
      var colors = randomColor({
        count: _.toArray(this.popularProductsData).length,
        luminosity: 'light',
        hue: 'random'
      });
      
      return {
        labels: _.keys(this.popularProductsData),
        datasets: [
          {
            data: _.values(this.popularProductsData),
            backgroundColor: colors,
            hoverBackgroundColor: colors
          }
        ]
      };
    }
  },
  
  data() {
    return {
      currentYear: moment().format('YYYY'),
      filters: {
        year: moment().format('YYYY')
      },
      ordersData: [],
      incomeData: [],
      popularProductsData: [],
      isLoading: false,
      loadingOptions: {
        text: loadAwesome.ballSpin
      },
      popularProductsChartLegend: ''
    }
  },
  
  ready() {
    this.loadData();
  },
  
  methods: {
    
    buildStoreStatsChart() {
      var ctx = document.getElementById('js-store-stats-chart').getContext('2d');
      
      if (typeof window.storeStatsChart != 'undefined') {
        window.storeStatsChart.destroy();
      }
      
      window.storeStatsChart = new Chart(ctx, {
        type: 'bar',
        data: this.storeStatsChartData,
        options: {
          maintainAspectRatio: false,
          responsive: true,
          tooltips: {
            mode: 'label',
            callbacks: {
              label: (tooltipItem, data) => {
                if (tooltipItem.datasetIndex == 0) {
                  return `${Lang.get('labels.orders')}: ${tooltipItem.yLabel}`;
                }
                else {
                  return `${Lang.get('labels.total')}: $${tooltipItem.yLabel}`;
                }
              }
            }
          },
          elements: {
            line: {
              fill: false
            }
          },
          scales: {
            xAxes: [{
              display: true,
              gridLines: {
                display: false
              },
              labels: {
                show: true
              }
            }],
            yAxes: [
              {
                type: 'linear',
                display: true,
                position: 'left',
                id: 'y-axis-orders',
                gridLines: {
                  display: true,
                  lineWidth: 0.5
                },
                labels: {
                  show: true
                },
                ticks: {
                  beginAtZero: true,
          
                  // Return an empty string to draw the tick line but hide the tick label
                  // Return `null` or `undefined` to hide the tick line entirely
                  userCallback: (value, index, values) => {
                    // Convert the number to a string and splite the string every 3 charaters from the end
                    
                    if (value % 5 == 0) {
                      value = value.toString();
                      return Lang.get('labels.orders') + ' ' + value;
                    }
                  }
                }
              }, {
                type: 'linear',
                display: true,
                position: 'right',
                id: 'y-axis-total',
                gridLines: {
                  display: false
                },
                labels: {
                  show: true        
                },
                ticks: {
                  beginAtZero: true,
          
                  // Return an empty string to draw the tick line but hide the tick label
                  // Return `null` or `undefined` to hide the tick line entirely
                  userCallback: (value, index, values) => {
                    value = money.floatToAmount(value);
                    // Convert the number to a string and splite the string every 3 charaters from the end
                    value = value.toString();
                    return '$' + value;
                  }
                }
              }
            ]
          }
        }
      });
    },
    
    buildPopularProductsChart() {
      var ctx = document.getElementById('js-popular-products-chart').getContext('2d');
      
      if (typeof window.popularProductsChart != 'undefined') {
        window.popularProductsChart.destroy();
      }
      
      window.popularProductsChart = new Chart(ctx, {
        type: 'doughnut',
        data: this.popularProductsChartData,
        options: {
          maintainAspectRatio: false,
          responsive: true,
          legend: {
            display: false
          }
        }
      });
      this.popularProductsChartLegend = window.popularProductsChart.generateLegend();
    },
    
    filterByYear(year) {
      this.filters.year = year;
      this.loadData();
    },
    
    loadData() {
      this.isLoading = true;
      App.models.reports.getData($('#store_id').val(), this.filters, (response) => {
        this.isLoading = false;
        
        this.ordersData = response.data.ordersData;
        this.incomeData = response.data.incomeData;
        this.popularProductsData = response.data.popularProductsData;
        
        this.buildStoreStatsChart();
        this.buildPopularProductsChart();
      }, () => {
        this.isLoading = false;
      });
    },
    
  }
});

