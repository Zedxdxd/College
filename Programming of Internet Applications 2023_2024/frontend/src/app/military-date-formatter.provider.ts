import { CalendarDateFormatter, DateFormatterParams } from 'angular-calendar';
import { formatDate } from '@angular/common';
import { Injectable } from '@angular/core';

@Injectable()
export class MilitaryDateFormatter extends CalendarDateFormatter {
  // you can override any of the methods defined in the parent class

  public override dayViewHour({ date, locale }: DateFormatterParams): string {
    if (locale === undefined) locale = "";
    return formatDate(date, 'HH:mm', locale);
  }

  public override weekViewHour({ date, locale }: DateFormatterParams): string {
    return this.dayViewHour({ date, locale });
  }
}
