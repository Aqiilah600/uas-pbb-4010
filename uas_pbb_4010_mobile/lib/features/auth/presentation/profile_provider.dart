import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../data/auth_repository.dart';

final profileProvider = FutureProvider<Map<String, dynamic>>((ref) async {
  return AuthRepository().getProfile();
});
