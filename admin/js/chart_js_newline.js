Chart.pluginService.register({
  beforeInit: function(chart) {
    var hasWrappedTicks = chart.config.data.labels.some(function(label) {
      return label.indexOf('\n') !== -1;
    });

    if (hasWrappedTicks) {
      // figure out how many lines we need - use fontsize as the height of one line
      var tickFontSize = Chart.helpers.getValueOrDefault(chart.options.scales.xAxes[0].ticks.fontSize, Chart.defaults.global.defaultFontSize);
      var maxLines = chart.config.data.labels.reduce(function(maxLines, label) {
        return Math.max(maxLines, label.split('\n').length);
      }, 0);
      var height = (tickFontSize + 2) * maxLines + (chart.options.scales.xAxes[0].ticks.padding || 0);

      // insert a dummy box at the bottom - to reserve space for the labels
      Chart.layoutService.addBox(chart, {
        draw: Chart.helpers.noop,
        isHorizontal: function() {
          return true;
        },
        update: function() {
          return {
            height: this.height
          };
        },
        height: height,
        options: {
          position: 'bottom',
          fullWidth: 1,
        }
      });

      // turn off x axis ticks since we are managing it ourselves
      chart.options = Chart.helpers.configMerge(chart.options, {
        scales: {
          xAxes: [{
            ticks: {
              display: false,
              // set the fontSize to 0 so that extra labels are not forced on the right side
              fontSize: 0
            }
          }]
        }
      });

      chart.hasWrappedTicks = {
        tickFontSize: tickFontSize
      };
    }
  },
  afterDraw: function(chart) {
    if (chart.hasWrappedTicks) {
      // draw the labels and we are done!
      chart.chart.ctx.save();
      var tickFontSize = chart.hasWrappedTicks.tickFontSize;
      var tickFontStyle = Chart.helpers.getValueOrDefault(chart.options.scales.xAxes[0].ticks.fontStyle, Chart.defaults.global.defaultFontStyle);
      var tickFontFamily = Chart.helpers.getValueOrDefault(chart.options.scales.xAxes[0].ticks.fontFamily, Chart.defaults.global.defaultFontFamily);
      var tickLabelFont = Chart.helpers.fontString(tickFontSize, tickFontStyle, tickFontFamily);
      chart.chart.ctx.font = tickLabelFont;
      chart.chart.ctx.textAlign = 'center';
      var tickFontColor = Chart.helpers.getValueOrDefault(chart.options.scales.xAxes[0].fontColor, Chart.defaults.global.defaultFontColor);
      chart.chart.ctx.fillStyle = tickFontColor;

      var meta = chart.getDatasetMeta(0);
      var xScale = chart.scales[meta.xAxisID];
      var yScale = chart.scales[meta.yAxisID];

      chart.config.data.labels.forEach(function(label, i) {
        label.split('\n').forEach(function(line, j) {
          chart.chart.ctx.fillText(line, xScale.getPixelForTick(i + 0.5), (chart.options.scales.xAxes[0].ticks.padding || 0) + yScale.getPixelForValue(yScale.min) +
            // move j lines down
            j * (chart.hasWrappedTicks.tickFontSize + 2));
        });
      });

      chart.chart.ctx.restore();
    }
  }
});