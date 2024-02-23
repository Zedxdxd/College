import { ComponentFixture, TestBed } from '@angular/core/testing';

import { UcenikKalendarComponent } from './ucenik-kalendar.component';

describe('UcenikKalendarComponent', () => {
  let component: UcenikKalendarComponent;
  let fixture: ComponentFixture<UcenikKalendarComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [UcenikKalendarComponent]
    });
    fixture = TestBed.createComponent(UcenikKalendarComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
