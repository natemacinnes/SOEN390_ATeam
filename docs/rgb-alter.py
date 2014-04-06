"""
This file was used to derive color schemes: it creates lighter or darker
variants of the provided RGB color given a brightness multiplier.
"""

def multiply_rgb(color, alter):
  """Accepts a HTML RGB color and alters each color channel."""
  rgb = color[1:]
  chunks, chunk_size = len(rgb), len(rgb)/3
  r, g, b = [ int(int('0x%s' % rgb[i:i+chunk_size], 0) * alter) for i in range(0, chunks, chunk_size) ]
  return '#%.2x%.2x%.2x' % (r, g, b)

darken = 0.8
print multiply_rgb('#ffffff', darken)
