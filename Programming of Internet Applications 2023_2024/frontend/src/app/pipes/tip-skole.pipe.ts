import { Pipe, PipeTransform } from '@angular/core';

@Pipe({
  name: 'tipSkole'
})
export class TipSkolePipe implements PipeTransform {

  transform(tipSkole: string): string {
    if (tipSkole == "osn"){
      return "Osnovna";
    }
    if (tipSkole == "srGimn") {
      return "Srednja - gimnazija";
    }
    if (tipSkole == "srStruc") {
      return "Srednja - strucna";
    }
    if (tipSkole == "srUmet") {
      return "Srednja - umetnicka"
    }

    return "";
  }

}
