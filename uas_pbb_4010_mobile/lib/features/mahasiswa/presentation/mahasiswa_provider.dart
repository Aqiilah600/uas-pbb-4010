import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../data/mahasiswa_model.dart';
import '../data/mahasiswa_repository.dart';

final mahasiswaListProvider = FutureProvider<List<Mahasiswa>>((ref) async {
  final repository = MahasiswaRepository();
  return repository.getMahasiswaList();
});
