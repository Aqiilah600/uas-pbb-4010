import 'dart:typed_data';
import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:image_picker/image_picker.dart';
import '../data/mahasiswa_repository.dart';
import 'mahasiswa_provider.dart';

class TambahMahasiswaPage extends ConsumerStatefulWidget {
  const TambahMahasiswaPage({super.key});

  @override
  ConsumerState<TambahMahasiswaPage> createState() =>
      _TambahMahasiswaPageState();
}

class _TambahMahasiswaPageState extends ConsumerState<TambahMahasiswaPage> {
  final _formKey = GlobalKey<FormState>();
  final _namaController = TextEditingController();
  final _nimController = TextEditingController();
  final _tanggalController = TextEditingController();
  final _angkatanController = TextEditingController();
  String _jenisKelamin = 'L';
  XFile? _fotoProfil;
  Uint8List? _fotoBytes;
  bool _isLoading = false;

  final Map<String, int> _hobiOptions = {'Bola': 1, 'Baca': 2, 'Menyanyi': 3};
  final Set<int> _selectedHobiIds = {};

  Future<void> _pickImage() async {
    final picker = ImagePicker();
    final picked = await picker.pickImage(source: ImageSource.gallery);
    if (picked != null) {
      final bytes = await picked.readAsBytes();
      setState(() {
        _fotoProfil = picked;
        _fotoBytes = bytes;
      });
    }
  }

  Future<void> _pickDate() async {
    final picked = await showDatePicker(
      context: context,
      initialDate: DateTime(2000),
      firstDate: DateTime(1990),
      lastDate: DateTime.now(),
    );
    if (picked != null) {
      _tanggalController.text =
          '${picked.year}-${picked.month.toString().padLeft(2, '0')}-${picked.day.toString().padLeft(2, '0')}';
    }
  }

  Future<void> _submit() async {
    if (!_formKey.currentState!.validate()) return;

    setState(() => _isLoading = true);
    try {
      await MahasiswaRepository().createMahasiswa(
        namaLengkap: _namaController.text.trim(),
        nim: _nimController.text.trim(),
        tanggalLahir: _tanggalController.text.trim(),
        jenisKelamin: _jenisKelamin,
        angkatan: _angkatanController.text.trim(),
        hobiIds: _selectedHobiIds.toList(),
        fotoProfil: _fotoProfil,
      );

      ref.invalidate(mahasiswaListProvider);

      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Data mahasiswa berhasil ditambahkan.')),
      );
      Navigator.pop(context);
    } catch (e) {
      if (!mounted) return;
      ScaffoldMessenger.of(
        context,
      ).showSnackBar(SnackBar(content: Text('Gagal menambahkan data: $e')));
    } finally {
      if (mounted) setState(() => _isLoading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Tambah Mahasiswa')),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Form(
          key: _formKey,
          child: ListView(
            children: [
              GestureDetector(
                onTap: _pickImage,
                child: CircleAvatar(
                  radius: 48,
                  backgroundImage: _fotoBytes != null
                      ? MemoryImage(_fotoBytes!)
                      : null,
                  child: _fotoBytes == null
                      ? const Icon(Icons.add_a_photo, size: 32)
                      : null,
                ),
              ),
              const SizedBox(height: 16),
              TextFormField(
                controller: _namaController,
                decoration: const InputDecoration(
                  labelText: 'Nama Lengkap',
                  border: OutlineInputBorder(),
                ),
                validator: (v) => v == null || v.isEmpty ? 'Wajib diisi' : null,
              ),
              const SizedBox(height: 12),
              TextFormField(
                controller: _nimController,
                decoration: const InputDecoration(
                  labelText: 'NIM',
                  border: OutlineInputBorder(),
                ),
                validator: (v) => v == null || v.isEmpty ? 'Wajib diisi' : null,
              ),
              const SizedBox(height: 12),
              TextFormField(
                controller: _tanggalController,
                readOnly: true,
                onTap: _pickDate,
                decoration: const InputDecoration(
                  labelText: 'Tanggal Lahir',
                  border: OutlineInputBorder(),
                  suffixIcon: Icon(Icons.calendar_today),
                ),
                validator: (v) => v == null || v.isEmpty ? 'Wajib diisi' : null,
              ),
              const SizedBox(height: 12),
              Row(
                children: [
                  Expanded(
                    child: RadioListTile<String>(
                      title: const Text('L'),
                      value: 'L',
                      groupValue: _jenisKelamin,
                      onChanged: (v) => setState(() => _jenisKelamin = v!),
                    ),
                  ),
                  Expanded(
                    child: RadioListTile<String>(
                      title: const Text('P'),
                      value: 'P',
                      groupValue: _jenisKelamin,
                      onChanged: (v) => setState(() => _jenisKelamin = v!),
                    ),
                  ),
                ],
              ),
              TextFormField(
                controller: _angkatanController,
                decoration: const InputDecoration(
                  labelText: 'Angkatan',
                  border: OutlineInputBorder(),
                ),
                keyboardType: TextInputType.number,
                validator: (v) => v == null || v.isEmpty ? 'Wajib diisi' : null,
              ),
              const SizedBox(height: 12),
              const Text('Hobi', style: TextStyle(fontWeight: FontWeight.bold)),
              Wrap(
                spacing: 8,
                children: _hobiOptions.entries.map((entry) {
                  final selected = _selectedHobiIds.contains(entry.value);
                  return FilterChip(
                    label: Text(entry.key),
                    selected: selected,
                    onSelected: (val) {
                      setState(() {
                        if (val) {
                          _selectedHobiIds.add(entry.value);
                        } else {
                          _selectedHobiIds.remove(entry.value);
                        }
                      });
                    },
                  );
                }).toList(),
              ),
              const SizedBox(height: 24),
              ElevatedButton(
                onPressed: _isLoading ? null : _submit,
                child: _isLoading
                    ? const SizedBox(
                        height: 20,
                        width: 20,
                        child: CircularProgressIndicator(strokeWidth: 2),
                      )
                    : const Text('Simpan'),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
