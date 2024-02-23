import { ComponentFixture, TestBed } from '@angular/core/testing';

import { NastavniciComponent } from './nastavnici.component';

describe('NastavniciComponent', () => {
  let component: NastavniciComponent;
  let fixture: ComponentFixture<NastavniciComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [NastavniciComponent]
    });
    fixture = TestBed.createComponent(NastavniciComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
