import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AdminStatistikaComponent } from './admin-statistika.component';

describe('AdminStatistikaComponent', () => {
  let component: AdminStatistikaComponent;
  let fixture: ComponentFixture<AdminStatistikaComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [AdminStatistikaComponent]
    });
    fixture = TestBed.createComponent(AdminStatistikaComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
