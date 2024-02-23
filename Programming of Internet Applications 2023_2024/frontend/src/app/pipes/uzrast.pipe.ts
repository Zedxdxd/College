import { Pipe, PipeTransform } from '@angular/core';

@Pipe({
  name: 'uzrast'
})
export class UzrastPipe implements PipeTransform {

  transform(uzrast: string): string{
    if (uzrast == 'osn14'){
      return "Osnovna skola(1-4. razred)";
    }
    if (uzrast == 'osn58'){
      return "Osnovna skola(5-8. razred)";
    }
    if (uzrast == 'sr14'){
      return "Srednja skola";
    }
    return "";
  }

}
