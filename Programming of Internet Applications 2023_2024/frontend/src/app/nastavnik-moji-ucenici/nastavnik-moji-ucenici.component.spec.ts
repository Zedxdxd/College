import { ComponentFixture, TestBed } from '@angular/core/testing';

import { NastavnikMojiUceniciComponent } from './nastavnik-moji-ucenici.component';

describe('NastavnikMojiUceniciComponent', () => {
  let component: NastavnikMojiUceniciComponent;
  let fixture: ComponentFixture<NastavnikMojiUceniciComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [NastavnikMojiUceniciComponent]
    });
    fixture = TestBed.createComponent(NastavnikMojiUceniciComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
