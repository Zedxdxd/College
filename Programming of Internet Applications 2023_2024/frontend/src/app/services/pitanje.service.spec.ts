import { TestBed } from '@angular/core/testing';

import { PitanjeService } from './pitanje.service';

describe('PitanjeService', () => {
  let service: PitanjeService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(PitanjeService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
