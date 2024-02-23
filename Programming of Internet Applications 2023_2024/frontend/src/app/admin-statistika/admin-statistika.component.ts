import { Component, OnInit, ViewChild } from '@angular/core';
import {
  ApexAxisChartSeries,
  ApexChart,
  ChartComponent,
  ApexDataLabels,
  ApexPlotOptions,
  ApexYAxis,
  ApexLegend,
  ApexStroke,
  ApexXAxis,
  ApexFill,
  ApexTooltip,
  ApexTitleSubtitle,
  ApexTheme,
  ApexNonAxisChartSeries,
  ApexResponsive,
  ApexGrid
} from "ng-apexcharts";
import { PredmetService } from '../services/predmet.service';
import { KorisnikService } from '../services/korisnik.service';
import { CasService } from '../services/cas.service';
import { Korisnik } from '../models/korisnik';
import { Router } from '@angular/router';

export type DijagramBarOptions = {
  series: ApexAxisChartSeries;
  chart: ApexChart;
  dataLabels: ApexDataLabels;
  plotOptions: ApexPlotOptions;
  yaxis: ApexYAxis;
  xaxis: ApexXAxis;
  fill: ApexFill;
  tooltip: ApexTooltip;
  stroke: ApexStroke;
  legend: ApexLegend;
  title: ApexTitleSubtitle;
  theme: ApexTheme
};

export type DijagramPitaOptions = {
  series: ApexNonAxisChartSeries;
  chart: ApexChart;
  responsive: ApexResponsive[];
  labels: any;
  theme: ApexTheme;
};

type HistogramApexXAxis = {
  type?: "category" | "datetime" | "numeric";
  categories?: any;
  labels?: {
    style?: {
      colors?: string | string[];
      fontSize?: string;
    };
  };
};

export type DijagramHistogramOptions = {
  series: ApexAxisChartSeries;
  chart: ApexChart;
  dataLabels: ApexDataLabels;
  plotOptions: ApexPlotOptions;
  yaxis: ApexYAxis;
  xaxis: HistogramApexXAxis;
  grid: ApexGrid;
  colors: string[];
  legend: ApexLegend;
};

export type DijagramLinijaOptions = {
  series: ApexAxisChartSeries;
  chart: ApexChart;
  xaxis: ApexXAxis;
  dataLabels: ApexDataLabels;
  grid: ApexGrid;
  stroke: ApexStroke;
  title: ApexTitleSubtitle;
};

export type DijagramHorizontalBarOptions = {
  series: ApexAxisChartSeries;
  chart: ApexChart;
  dataLabels: ApexDataLabels;
  plotOptions: ApexPlotOptions;
  xaxis: ApexXAxis;
};

export type DijagramPolarOptions = {
  series: ApexNonAxisChartSeries;
  chart: ApexChart;
  responsive: ApexResponsive[];
  labels: any;
  stroke: ApexStroke;
  fill: ApexFill;
};

@Component({
  selector: 'app-admin-statistika',
  templateUrl: './admin-statistika.component.html',
  styleUrls: ['./admin-statistika.component.css']
})
export class AdminStatistikaComponent implements OnInit {
  @ViewChild("dijagramBar") dijagramBar!: ChartComponent;
  @ViewChild("dijagramLinija") dijagramLinija!: ChartComponent;
  @ViewChild("dijagramHorizontalBar") dijagramHorizontalBar!: ChartComponent;
  public dijagramBarOptions: Partial<DijagramBarOptions>;
  public dijagramPitaNastavniciOptions: Partial<DijagramPitaOptions>;
  public dijagramPitaUceniciOptions: Partial<DijagramPitaOptions>;
  public dijagramHistogramOptions: Partial<DijagramHistogramOptions>;
  public dijagramLinijaOptions: Partial<DijagramLinijaOptions>;
  public dijagramHorizontalBarOptions: Partial<DijagramHorizontalBarOptions>;
  public dijagramPolarOptions: Partial<DijagramPolarOptions>;

  ngOnInit(): void {
    let x = localStorage.getItem("ulogovan");
    if (x == null){
      this.router.navigate(['']);
    }
    else {
      let ulogovan: Korisnik = JSON.parse(x);
      if (ulogovan.tip == "nastavnik"){
        this.router.navigate(['nastavnikProfil']);
      }
      else if (ulogovan.tip == 'ucenik'){
        this.router.navigate(['ucenikProfil']);
      }
    }
  }

  constructor(private router: Router,
    private predmetServis: PredmetService,
    private korisnikServis: KorisnikService,
    private casServis: CasService) {

    // dijagramBar
    this.dijagramBarOptions = {
      series: [
      ],
      chart: {
        type: "bar",
        height: 350
      },
      plotOptions: {
        bar: {
          horizontal: false,
          columnWidth: "55%"
        },
      },
      dataLabels: {
        enabled: false
      },
      stroke: {
        show: false,
        width: 2,
        colors: ["transparent"]
      },
      xaxis: {
        categories: []
      },
      yaxis: {
        title: {
          text: "Broj nastavnika"
        }
      },
      fill: {
        opacity: 1
      },
      tooltip: {
        y: {
          formatter: function (val) {
            if (val == 1) {
              return "1 nastavnik"
            }
            return val + " nastavnika";
          }
        }
      },
      theme: {
        palette: "palette5"
      }
    };
    let mySeries: { name: string, data: number[] }[] = [];
    mySeries[0] = {
      name: "Osnovna skola(1-4. razred)",
      data: []
    };
    mySeries[1] = {
      name: "Osnovna skola(5-8. razred)",
      data: []
    }
    mySeries[2] = {
      name: "Srednja skola",
      data: []
    }
    predmetServis.dohvatiSvePredmete().subscribe(
      (sviPredmeti) => {
        sviPredmeti.forEach(predmet => {
          this.dijagramBarOptions.xaxis?.categories.push(predmet.naziv);
          let mapaUzrasti: Map<string, number> = new Map();
          mapaUzrasti.set("osn14", 0);
          mapaUzrasti.set("osn58", 0);
          mapaUzrasti.set("sr14", 0);

          predmet.nastavnici.forEach(nastavnik => {
            if (nastavnik.odobrenaRegistracija == "da") {
              nastavnik.predajeUzrastu.forEach(uzrast => {
                let x = mapaUzrasti.get(uzrast);
                if (x !== undefined) {
                  mapaUzrasti.set(uzrast, x + 1);
                }
              });
            }
          });

          let x = mapaUzrasti.get("osn14");
          if (x !== undefined) {
            mySeries[0].data.push(x)
          }
          x = mapaUzrasti.get("osn58");
          if (x !== undefined) {
            mySeries[1].data.push(x)
          }
          x = mapaUzrasti.get("sr14");
          if (x !== undefined) {
            mySeries[2].data.push(x)
          }
        })
        this.dijagramBarOptions.series = mySeries;
        this.dijagramBar.updateOptions(this.dijagramBarOptions);
      }
    )

    // dijagram pita nastavnici
    this.dijagramPitaNastavniciOptions = {
      series: [],
      chart: {
        width: 380,
        type: "pie"
      },
      labels: ["Zenski", "Muski"],
      responsive: [
        {
          breakpoint: 480,
          options: {
            chart: {
              width: 200
            },
            legend: {
              position: "bottom"
            }
          }
        }
      ],
      theme: {
        palette: "palette5"
      }
    };
    korisnikServis.procenatPolovaNastavnika().subscribe(
      (data) => {
        let numM = data[0].prosecnaOcena;
        let numZ = data[1].prosecnaOcena;
        let procZ = numZ / (numZ + numM) * 100
        let procM = numM / (numZ + numM) * 100
        this.dijagramPitaNastavniciOptions.series?.push(Number.parseFloat(procZ.toFixed(2)));
        this.dijagramPitaNastavniciOptions.series?.push(Number.parseFloat(procM.toFixed(2)));
      }
    )

    // dijagram pita ucenici
    this.dijagramPitaUceniciOptions = {
      series: [],
      chart: {
        width: 380,
        type: "pie"
      },
      labels: ["Zenski", "Muski"],
      responsive: [
        {
          breakpoint: 480,
          options: {
            chart: {
              width: 200
            },
            legend: {
              position: "bottom"
            }
          }
        }
      ],
      theme: {
        palette: "palette9"
      }
    };
    korisnikServis.procenatPolovaUcenika().subscribe(
      (data) => {
        let numM = data[0].prosecnaOcena;
        let numZ = data[1].prosecnaOcena;
        let procZ = numZ / (numZ + numM) * 100
        let procM = numM / (numZ + numM) * 100
        this.dijagramPitaUceniciOptions.series?.push(Number.parseFloat(procZ.toFixed(2)));
        this.dijagramPitaUceniciOptions.series?.push(Number.parseFloat(procM.toFixed(2)));
      }
    )

    // dijagram histogram
    this.dijagramHistogramOptions = {
      series: [
        {
          name: "Broj casova",
          data: []
        }
      ],
      chart: {
        height: 350,
        type: "bar",
        events: {
          click: function (chart, w, e) {
            console.log(w)
          }
        }
      },
      colors: [
        "#008FFB",
        "#00E396",
        "#FEB019",
        "#FF4560",
        "#775DD0",
        "#546E7A",
        "#26a69a"
      ],
      plotOptions: {
        bar: {
          columnWidth: "45%",
          distributed: true
        }
      },
      dataLabels: {
        enabled: false
      },
      legend: {
        show: false
      },
      grid: {
        show: false
      },
      xaxis: {
        categories: [
          "Ponedeljak",
          "Utorak",
          "Sreda",
          "Cetvrtak",
          "Petak",
          "Subota",
          "Nedelja"
        ],
        labels: {
          style: {
            colors: [
              "#008FFB",
              "#00E396",
              "#FEB019",
              "#FF4560",
              "#775DD0",
              "#546E7A",
              "#26a69a"
            ],
            fontSize: "12px"
          }
        }
      }
    };
    casServis.prosecanBrojCasovaPoDanu().subscribe(
      (data) => {
        let mapaCasova: Map<number, number> = new Map();
        for (let i = -1; i <= 5; i++) {
          mapaCasova.set(i, 0);
        }
        data.forEach(elem => {
          mapaCasova.set(elem.razred, Number.parseFloat(elem.prosecnaOcena.toFixed(4)));
        });
        let mySeries: { name: string, data: number[] }[] = [];
        mySeries[0] = {
          name: "Broj casova",
          data: []
        }
        mapaCasova.forEach((value, key) => {
          mySeries[0].data.push(value);
        });
        this.dijagramHistogramOptions.series = mySeries;
      }
    )

    //dijagram linija
    this.dijagramLinijaOptions = {
      series: [
      ],
      chart: {
        height: 350,
        type: "line",
        zoom: {
          enabled: false
        }
      },
      dataLabels: {
        enabled: false
      },
      stroke: {
        curve: "straight"
      },
      grid: {
        row: {
          colors: ["#f3f3f3", "transparent"], // takes an array which will be repeated on columns
          opacity: 0.5
        }
      },
      xaxis: {
        categories: [
          "Januar",
          "Februar",
          "Mart",
          "April",
          "Maj",
          "Jun",
          "Jul",
          "Avgust",
          "Septembar",
          "Oktobar",
          "Novembar",
          "Decembar"
        ]
      }
    };
    casServis.topDesetAngazovanihNastavnika().subscribe(
      (data) => {
        let mySeries: { name: string, data: number[] }[] = [];
        data.forEach(elem => {
          korisnikServis.dohvatiKorisnika(elem.korime).subscribe(
            (kor) => {
              let name = kor.ime + " " + kor.prezime;
              mySeries.push({
                name: name,
                data: elem.angazovanja
              })
              this.dijagramLinijaOptions.series = mySeries;
              this.dijagramLinija.updateOptions(this.dijagramLinijaOptions);
            }
          )
        })
      }
    )

    // dijagram horizontal bar
    this.dijagramHorizontalBarOptions = {
      series: [
      ],
      chart: {
        type: "bar",
        height: 350
      },
      plotOptions: {
        bar: {
          horizontal: true
        }
      },
      dataLabels: {
        enabled: false
      },
      xaxis: {
        categories: [
        ]
      }
    };
    casServis.brojOdrzanihCasovaPoPredmetu().subscribe(
      (data) => {
        let mySeries: { name: string, data: number[] }[] = [];
        mySeries[0] = {
          name: "Broj casova",
          data: []
        }

        data.forEach(elem => {
          this.dijagramHorizontalBarOptions.xaxis?.categories.push(elem.predmet);
          mySeries[0].data.push(elem.broj);
        });

        this.dijagramHorizontalBarOptions.series = mySeries;
      }
    )


    // dijagram polar
    this.dijagramPolarOptions = {
      series: [],
      chart: {
        type: "polarArea"
      },
      stroke: {
        colors: ["#fff"]
      },
      labels: ["Osnovna skola(1-4. razred)", "Osnovna skola(5-8. razred)", "Srednja skola"],
      fill: {
        opacity: 0.8
      },
      responsive: [
        {
          breakpoint: 480,
          options: {
            chart: {
              width: 200
            },
            legend: {
              position: "bottom"
            }
          }
        }
      ]
    };
    korisnikServis.procenatUzrastaUcenika().subscribe(
      (data) => {
        this.dijagramPolarOptions.series?.push(data[0].broj)
        this.dijagramPolarOptions.series?.push(data[1].broj)
        this.dijagramPolarOptions.series?.push(data[2].broj)

      }
    )
  }


}
